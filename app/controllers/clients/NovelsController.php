<?php

namespace HS\app\controllers\clients;

use DateTime;
use ErrorException;
use HS\app\models\client\items\DownloadPDFItem;
use HS\app\models\client\NovelModel;
use HS\app\models\client\TrackingModel;
use HS\app\models\items\CommentItem;
use HS\config\enums\AppDirs;
use HS\config\enums\SubDomains;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\HTMLFilter;
use HS\libs\helpers\Json;
use HS\libs\helpers\Logger;
use HS\libs\helpers\UrlMaker;
use HS\libs\io\Path;
use HS\libs\io\Url;
use HS\libs\net\Http;
use HS\libs\net\HttpStatus;
use HS\libs\view\Template;
use HS\libs\view\View;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use PDOException;

class NovelsController
{
    public function Index(): never
    {
        try {
            $model = new NovelModel();
            $state_count = $model->GetStateCounts();
            $last_chapters = $model->GetLastChapters(8, 0);
            $last_novels = $model->GetLast(8, 0);
            $most_popular_novel = $model->GetMostPopularList(5);
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        $data = View::GetClientData();
        $data->NovelStateCount = $state_count;
        $data->LastChapters = $last_chapters;
        $data->LastNovels = $last_novels;
        $data->MostPopularNovels = $most_popular_novel;

        View::GetLayout()
            ->SetTitle('Novelas')
            ->AddStyle('pages/novel/index')
            ->AddSection('header', '/novel/sections/header')
            ->AddSection('main', '/novel/index');

        Template::CallClient('template');
    }

    public function Item(string $project): never
    {
        try {
            $session = Session::GetOnlyRead();
            $model = new NovelModel();

            //Si la novela no existe...
            if (empty($novel = $model->Get($project, $session->IsLogin() ? $session->User->ID : null))) {
                Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
                die;
            }

            $categories = $model->GetCategories($novel->ID);
            $chapters = $model->GetAllChapters($novel->ID);
            $groups = $model->GetChapterGroups($novel->ID);
            $circle = $model->GetNovelsCount($novel->CircleID);
            $comments = $model->GetAllComments($novel->ID, null);

            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Realizando seguimiento.
        TrackingModel::Project($novel->CircleID, $novel->ID);

        //Ordenando entradas y agrupándolas.
        $entries = [];
        foreach ($chapters as $entry) {
            if ($entry->Group != 0) {
                $group = "G-$entry->Group";
                if (!isset($entries[$group]))
                    $entries[$group] = [];
                $entries[$group][] = $entry;
            } else
                $entries[] = $entry;
        }

        //Guardando datos.
        $data = View::GetClientData();
        $data->Novel = $novel;
        $data->Novel->Categories = $categories;
        $data->Novel->Groups = call_user_func_array('array_merge', array_map(fn($group) => ["G-$group->ID" => $group], $groups));
        $data->Novel->Entries = $entries;
        $data->Circle = $circle;
        $data->Comments = $comments;

        //Plantilla.
        View::GetLayout()
            ->SetTitle(htmlspecialchars($novel->Title))
            ->AddStyle(['pages/novel/item', 'comments'])
            ->AddScript(['pages/item', 'comments'])
            ->AddSection('main', '/novel/item');

        Template::CallClient('template');
    }

    public function Chapter(string $project, string $chapter): never
    {
        try {
            $model = new NovelModel();
            $novel = $model->Get($project, null);

            //Si la novela no existe...
            if (empty($novel)) {
                Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
                die;
            }

            //Obteniendo capítulo
            $chapter = $model->GetChapter($novel->ID, $chapter);

            //Si el capítulo no existe...
            if (empty($chapter)) {
                Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
                die;
            }

            $circle = $model->GetNovelsCount($novel->CircleID);
            $comments = $model->GetAllComments($novel->ID, $chapter->ID);
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Realizando seguimiento.
        TrackingModel::ProjectChapter($novel->CircleID, $novel->ID, $chapter->ID);

        $data = View::GetClientData();
        $data->Chapter = $chapter;
        $data->Circle = $circle;
        $data->Comments = $comments;

        View::GetLayout()
            ->SetTitle('Summoned Slaughterer | Lector de Novelas')
            ->AddSection('main', '/novel/chapter')
            ->AddStyle(['pages/novel/chapter', 'ckcontent', 'comments'])
            ->AddScript(['pages/chapter', 'comments']);

        Template::CallClient('template');
    }

    public function AddComment(string $project, string $chapter = null): never
    {
        Session::IfNoLoginRedirect();

        $parent = $_POST['code'] ?? null;
        if (!empty($parent)) {
            $parent = CommentItem::DecodeID($parent);
            if (filter_var($parent, FILTER_VALIDATE_INT) === false)
                die(Json::GetJsonWarning(1, 'Solicitud invalida'));
        }

        try {
            $model = new NovelModel();
            $project_id = $model->GetID($project);
            $comment = $model->AddComment(Session::GetOnlyRead()->User->ID, $project_id, $chapter, $_POST['c-text'], $parent);
            unset($model);

            die(Json::GetJsonSuccess(['code' => $comment->GetID(), 'isM' => $comment->IsMember]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function DownloadAll(string $project): never
    {
        try {
            $model = new NovelModel();
            $project = $model->GetDataForPDF($project, null);

            //Si la novela no existe...
            if (is_null($project)) {
                Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
                die;
            }

            //Estableciendo encabezados de caché.
            $this->SendCacheHeaderForPDF($project->ModifiedDate);

            //Si existen grupos entonces no permitir descargar todos los capítulos.
            $groups = $model->GetChapterGroups($project->ID);
            if (count($groups) > 0) {
                Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
                die;
            }

            //Obteniendo todos los capítulos.
            $chapters = $model->GetAllChaptersWithContent($project->ID);
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Generando PDF.
        try {
            $this->LaunchPDFDownload($project, array_reverse($chapters), $project->Name . '.pdf');
        } catch (MpdfException|ErrorException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::PDF, $ex);
        }
    }

    public function DownloadGroup(string $project, int $group): never
    {
        try {
            $model = new NovelModel();
            $project = $model->GetDataForPDF($project, $group);

            //Si no existe el proyecto o el grupo.
            if (is_null($project)) {
                Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
                die;
            }

            //Estableciendo encabezados de caché.
            $this->SendCacheHeaderForPDF($project->ModifiedDate);

            //Obteniendo capítulos.
            $chapters = $model->GetAllChaptersWithContent($project->ID, $group);
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Generando PDF.
        try {
            $this->LaunchPDFDownload($project, array_reverse($chapters), "$project->Name-$group.pdf");
        } catch (MpdfException|ErrorException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::PDF, $ex);
        }
    }

    #Metodos privados.
    private function SendCacheHeaderForPDF(string $modified_date): void
    {
        header('Content-Description: File Transfer');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: public, must-revalidate, max-age=3600');
        header('Pragma: public');
        //header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . (new DateTime($modified_date))->format('D, d M Y H:i:s') . ' GMT');
        header_remove('Expires');
    }

    /**
     * @throws MpdfException|ErrorException
     */
    private function LaunchPDFDownload(DownloadPDFItem $project, array $chapters, string $filename): never
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $mpdf = $this->GetPDFInstance($project, $chapters);
        die($mpdf->OutputBinaryData());
    }

    /**
     * @throws MpdfException|ErrorException
     */
    private function GetPDFInstance(DownloadPDFItem $project, array $chapters): Mpdf
    {
        $url_base = UrlMaker::GetCurrentProtocol() . Url::Combine(SubDomains::root->value, $project->TypeName, $project->Name) . '/';

        $mpdf = new Mpdf([
            'tempDir' => Path::CombineRoot(AppDirs::PDF_CACHE),
            'pagenumPrefix' => 'Pag. ',
            'defaultPageNumStyle' => '1'
        ]);
        $mpdf->showImageErrors = true;
        $mpdf->SetHeader($project->Title);
        $mpdf->setFooter('{PAGENO}');
        $styles = file_get_contents(Path::CombineRoot('/public/files/css/ckcontent.pdf.css'));
        $styles .= 'h1{ text-align: center; }
                    img{
                        max-width: 100%;
                        max-height: 100%;
                    }
                    
                    .image-style-align-left{
                        margin-right: 20px;
                    }
                    
                    .image-style-align-right{
                        margin-left: 20px;
                    }';
        $mpdf->WriteHTML($styles, HTMLParserMode::HEADER_CSS);

        foreach ($chapters as $chapter) {
            $content = HTMLFilter::PurifyHTMLForPDF($chapter->Content, [
                'URI.Base' => $url_base,
                'URI.MakeAbsolute' => true
            ]);

            $content = preg_replace('/<p>((?:(?!<p(?: style=".*")*>).)*(?:!<\/p>.)*)<\/p>/', '$1', $content);

            $mpdf->AddPage();
            $mpdf->WriteHTML("<h1>$chapter->Name. $chapter->Title</h1>", HTMLParserMode::HTML_BODY);
            $mpdf->WriteHTML($content, HTMLParserMode::HTML_BODY);
        }

        return $mpdf;
    }
}