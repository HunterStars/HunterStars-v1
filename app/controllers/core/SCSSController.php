<?php

namespace HS\app\controllers\core;

use Error;
use Exception;
use HS\config\enums\AppDirs;
use HS\config\enums\SubDomains;
use HS\config\LogFile;
use HS\libs\helpers\AppFiles;
use HS\libs\helpers\IOUtils;
use HS\libs\helpers\Logger;
use HS\libs\helpers\MimeType;
use HS\libs\io\Path;
use HS\libs\io\Url;
use HS\libs\net\HttpResponse;
use ScssPhp\ScssPhp\CompilationResult;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\OutputStyle;
use const HS\APP_DEBUG;
use const HS\APP_PATH;
use const HS\config\SCSS_COMPRESS;
use const HS\config\SCSS_PATH_CACHE;
use const HS\config\SCSS_PATH_SOURCE;

require APP_PATH . '/vendor/SCSSPHP/scss.inc.php';

class SCSSController
{
    private function GetPathSCSS(string $filename): ?string
    {
        $path_scss = match ($_SERVER['SERVER_NAME']) {
            SubDomains::root->value, SubDomains::www->value => AppFiles::GetClientSCSS($filename),
            SubDomains::studio->value => AppFiles::GetAdminSCSS($filename),
            default => null,
        };

        if (!empty($path_scss) && file_exists($path_scss))
            return $path_scss;
        else {
            $path_scss = AppFiles::GetSCSS($filename);

            if (!empty($path_scss) && file_exists($path_scss))
                return $path_scss;
        }

        return null;
    }

    private function GetCompilationResult(string $filename): CompilationResult
    {
        //Determinando ubicación del SCSS.
        $path_scss = $this->GetPathSCSS($filename);

        if (!empty($path_scss)) {
            //Creando directorio para almacenar caché.
            $hasCache = IOUtils::CreateDirectory(SCSS_PATH_CACHE, true);

            //Configurando el compilador SCSS.
            $scss = new Compiler(!$hasCache ? [] : [
                'cacheDir' => SCSS_PATH_CACHE,
                'prefix' => 'scss_'
            ]);

            //Añadiendo controlador para los import de los scss.
            //$scss->setImportPaths(Path::CombineRoot(APP_DIRS::FILES));
            $scss->addImportPath(function ($request) {
                if (Compiler::isCssImport($request)) return null;

                $request_name = pathinfo($request, PATHINFO_BASENAME);
                $request_dir = pathinfo($request, PATHINFO_DIRNAME);
                $request = Path::CombineRoot(AppDirs::FILES, $request_dir, 'scss', "$request_name.scss");
                $request_ = Path::CombineRoot(AppDirs::FILES, $request_dir, 'scss', "_$request_name.scss");

                if (file_exists($request)) return $request;
                else if (file_exists($request_)) return $request_;
                else return null;
            });
            $scss->setOutputStyle(SCSS_COMPRESS ? OutputStyle::COMPRESSED : OutputStyle::EXPANDED);
            $scss->setSourceMap(APP_DEBUG ? Compiler::SOURCE_MAP_FILE : Compiler::SOURCE_MAP_NONE);
            $scss->setSourceMapOptions([
                'sourceMapURL' => Url::Trim(Path::Combine('/files/css', "$filename.css.map")),//Url accesible
                'sourceMapBasepath' => Url::Trim(Path::CombineRoot(AppDirs::FILES->value)), //Remover del path real.
                'sourceMapRootpath' => SCSS_PATH_SOURCE,
            ]);

            //Compilando.
            try {
                return $scss->compileString(file_get_contents($path_scss), $path_scss);
            } catch (SassException|Exception|Error $ex) { //ServerException y otras...
                $this->ErrorInternal($ex, $path_scss);
                die();
            }
        } else {
            $this->ErrorNotFound($path_scss ?? $filename, true);
            die();
        }
    }

    public function Get(string $filename): never
    {
        HttpResponse::SetContentType(MimeType::CSS);
        die($this->GetCompilationResult($filename)->getCss());
    }

    public function GetMap(string $filename): never
    {
        if (APP_DEBUG) {
            HttpResponse::SetContentType(MimeType::Json);
            die($this->GetCompilationResult($filename)->getSourceMap());
        } else {
            $this->ErrorNotFound("$filename.scss.map", log: false);
            die();
        }
    }

    public function GetSCSS(string $type, string $filename): never
    {
        if (APP_DEBUG) {
            $path_scss = $this->GetPathSCSS($filename);

            if (!empty($path_scss)) {
                try {
                    HttpResponse::SetContentType(MimeType::CSS);

                    die(file_get_contents($path_scss));
                } catch (Error $ex) {
                    $this->ErrorInternal($ex, $filename);
                }
            } else
                $this->ErrorNotFound($filename, true);
        } else
            $this->ErrorNotFound($filename, log: true);

        exit();
    }

    private function ErrorInternal(Error|Exception $ex, string $path): void
    {
        //Respuesta: Internal Server Error
        HttpResponse::C500_INTERNAL_SERVER_ERROR->SetCode();

        //Log.
        Logger::WriteException(LogFile::SCSS, $ex);
    }

    private function ErrorNotFound(string $path, bool $log): void
    {
        //Respuesta: Fichero no encontrado.
        HttpResponse::C404_NOTFOUND->SetCode();

        //Log.
        if ($log)
            Logger::WriteString(LogFile::SCSS, "SCSS_FILE_NO_EXIST | $path");
    }
}