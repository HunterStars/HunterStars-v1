<?php

namespace HS\app\controllers\core;

use HS\app\models\core\AuthModel;
use HS\config\enums\AppRegex;
use HS\config\enums\SubDomains;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\Json;
use HS\libs\helpers\Logger;
use HS\libs\helpers\Regex;
use HS\libs\net\Http;
use HS\libs\security\HashCrypt;
use HS\libs\view\Template;
use HS\libs\view\View;
use PDOException;

class RegisterController
{
    public function Index(): never
    {
        if (Session::GetOnlyRead()->IsLogin())
            Http::Redirect('/');

        View::GetLayout()
            ->SetTitle('Registrarse')
            ->AddSection('main', 'register')
            ->AddStyle('pages/auth')
            ->AddScript('pages/auth', SubDomains::root)
            ->HideLateralMenu()
            ->HideExtraMenu();

        Template::CallClient('template');
    }

    public function Action(): never
    {
        //Si la sesión está iniciada.
        if (Session::Get()->IsLogin())
            die(Json::GetJsonError(1, 'Sesión iniciada.'));

        //Obteniendo parámetros post.
        $first_name = $_POST['fname'] ?? '';
        $last_name = $_POST['lname'] ?? '';
        $nick = $_POST['user'] ?? '';
        $email = $_POST['email'] ?? '';
        $pass = $_POST['pass'] ?? '';
        $repass = $_POST['repass'] ?? '';

        if (!Regex::Match(AppRegex::UserName, $first_name))
            die(Json::GetJsonWarning(1, 'El campo nombres posee caracteres inválidos.'));
        else if (!Regex::Match(AppRegex::UserName, $last_name))
            die(Json::GetJsonWarning(2, 'El campo apellidos posee caracteres inválidos.'));
        else if (!Regex::Match(AppRegex::UserNick, $nick))
            die(Json::GetJsonWarning(3, 'El nombre de usuario no posee la longitud suficiente o tiene caracteres inválidos.'));
        else if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
            die(Json::GetJsonWarning(4, 'El correo proporcionado no es valido.'));
        else if (!Regex::Match(AppRegex::UserPass, $pass))
            die(Json::GetJsonWarning(5, 'La contraseña no posee la longitud suficiente o tiene caracteres no validos'));
        else if ($pass !== $repass)
            die(Json::GetJsonWarning(6, 'Las contraseñas especificadas no coinciden entre si.'));

        //Insertando datos en la base de datos.
        try {
            $hashed_pass = HashCrypt::Hash($pass);

            $auth = new AuthModel();
            if ($auth->ExistNick($nick)) {
                sleep(2);
                die(Json::GetJsonWarning(7, "El nombre de usuario ya esta siendo utilizado."));
            }
            if (!empty($email) && $auth->ExistEmail($email)) {
                sleep(2);
                die(Json::GetJsonWarning(8, "El correo ingresado ya ha sido vinculado a otra cuenta."));
            }
            $auth->Register($nick, $hashed_pass, $email, $first_name, $last_name);
            unset($auth);

            die(Json::GetJsonSuccess(['user' => htmlspecialchars($nick)]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }
}