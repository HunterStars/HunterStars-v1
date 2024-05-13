<?php

namespace HS\app\controllers\core;

use HS\app\models\core\AuthModel;
use HS\app\models\items\UserItem;
use HS\config\enums\SubDomains;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\Json;
use HS\libs\net\Http;
use HS\libs\view\Template;
use HS\libs\helpers\Logger;
use HS\libs\security\HashCrypt;
use HS\libs\view\View;
use PDOException;

class LoginController
{
    const ERR_USERDATA = 0;
    const ERR_QUERY = 1;
    const ERR_DEBUG = 2;

    public function Index(): never
    {
        if (Session::GetOnlyRead()->IsLogin())
            Http::Redirect('/');

        $user = $_GET['user'] ?? '';
        View::GetClientData()->CurrentNick = UserItem::IsValidNick($user, optional: true) ? $user : '';

        View::GetLayout()
            ->SetTitle('Iniciar Sesión')
            ->AddSection('main', 'login')
            ->AddStyle('pages/auth')
            ->AddScript('pages/auth', SubDomains::root)
            ->HideLateralMenu()
            ->HideExtraMenu();

        Template::CallClient('template');
    }

    public function ActionLogin(): never
    {
        //Obteniendo parámetros post.
        $nick = $_POST['user-name'] ?? '';
        $pass = $_POST['user-pass'] ?? '';

        //Realizando verificaciones de los datos POST.
        if (!UserItem::IsValidNick($nick, optional: false) || !UserItem::IsValidPassword($pass, optional: false))
            die(Json::GetJsonWarning(1, "Usuario o contraseña no valido."));

        //Si la sesión ya estaba iniciada.
        if (Session::Get()->IsLogin())
            die(Json::GetJsonSuccess());

        //Obteniendo contraseña almacenada en BD para el usuario especificado.
        try {
            //Obteniendo usuario.
            $model = new AuthModel();

            //Si el usuario no existe.
            if (is_null($user = $model->GetLogin($nick)))
                die(Json::GetJsonWarning(2, 'El usuario no existe.'));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        //Verificando inicio de sesión.
        if (password_verify($pass, $user->Pass)) {
            //Regenerando contraseña si es necesario (No importa si falla).
            if (HashCrypt::needReHash($user->Pass)) {
                if (($hashed_pass = HashCrypt::Hash($pass)) !== false) {
                    try {
                        $model->SetPassword($user->ID, $hashed_pass);
                    } catch (PDOException $ex) {
                        Logger::WriteException(LogFile::DB, $ex);
                    }
                } else
                    Logger::WriteString(LogFile::CRYPT, "PASSWORD_REHASH_ERROR | $nick | $pass | $user->Pass");
            }

            //Estableciendo datos de sesión en BD.
            try {
                $model->SetLastAccess($user->ID);
            } catch (PDOException $ex) {
                Logger::WriteException(LogFile::DB, $ex);
            }

            //Desconectando DB.
            unset($db);

            //Estableciendo datos de sesión.
            $session = Session::Get();
            $session->IP = $_SERVER['REMOTE_ADDR'];
            $session->Agent = $_SERVER['HTTP_USER_AGENT'];
            $session->User = $user;
            $session->User->Pass = null; //Quitando contraseña de la sesión.
            $session->Close();

            //Devolviendo respuesta.
            $IsExternalUrl = is_null($_SERVER['HTTP_REFERER']) || is_null(SubDomains::tryFrom(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)));
            die(Json::GetJsonSuccess(['returnUrl' => $IsExternalUrl ? '/' : parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH)]));
        } else {
            //Desconectando base de datos.
            unset($db);

            //Timeout, si la contraseña era incorrecta.
            sleep(2);

            //Devolviendo respuesta.
            die(Json::GetJsonWarning(3, 'Contraseña incorrecta.'));
        }
    }

    public function Logout(): never
    {
        Session::Get()->Kill();
        $IsExternalUrl = is_null($_SERVER['HTTP_REFERER']) || is_null(SubDomains::tryFrom(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)));
        Http::Redirect($IsExternalUrl ? '/' : parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
    }
}