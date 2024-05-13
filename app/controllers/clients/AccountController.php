<?php

namespace HS\app\controllers\clients;

use HS\app\models\client\FavoriteModel;
use HS\app\models\client\NovelModel;
use HS\app\models\client\UserModel;
use HS\app\models\core\AuthModel;
use HS\config\enums\AppRegex;
use HS\config\LogFile;
use HS\libs\core\Session;
use HS\libs\helpers\Json;
use HS\libs\helpers\Logger;
use HS\libs\helpers\Regex;
use HS\libs\net\Http;
use HS\libs\net\HttpStatus;
use HS\libs\security\HashCrypt;
use HS\libs\view\Template;
use HS\libs\view\View;
use PDOException;

class AccountController
{
    public function Index(): never
    {
        View::GetLayout()
            ->SetTitle('Mi cuenta')
            ->AddStyle('pages/settings')
            ->AddScript('pages/account')
            ->AddSection('main', '/settings');

        Template::CallClient('template');
    }

    public function ActionChangeGeneralInformation(): never
    {
        $first_name = $_POST['fname'];
        $last_name = $_POST['lname'];
        $nick = $_POST['uname'];
        $email = $_POST['email'];

        if (!Regex::Match(AppRegex::UserName, $first_name))
            die(Json::GetJsonWarning(1, 'El campo nombres posee caracteres inválidos.'));
        else if (!Regex::Match(AppRegex::UserName, $last_name))
            die(Json::GetJsonWarning(2, 'El campo apellidos posee caracteres inválidos.'));
        else if (!Regex::Match(AppRegex::UserNick, $nick))
            die(Json::GetJsonWarning(3, 'El nombre de usuario no posee la longitud suficiente o tiene caracteres inválidos.'));
        else if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
            die(Json::GetJsonWarning(4, 'El correo proporcionado no es valido.'));

        //Actualizando datos en la base de datos.
        try {
            //Obteniendo datos de sesión.
            $user = Session::Get()->User;

            $auth = new AuthModel();
            if ($user->Nick != $nick && $auth->ExistNick($nick)) {
                sleep(2);
                die(Json::GetJsonWarning(5, "El nombre de usuario ya esta siendo utilizado."));
            }
            if (!empty($email) && $user->Email != $email && $auth->ExistEmail($email)) {
                sleep(2);
                die(Json::GetJsonWarning(6, "El correo ingresado ya ha sido vinculado a otra cuenta."));
            }
            $result = $auth->ChangeGeneralInformation(Session::GetOnlyRead()->User->ID, $first_name, $last_name, $nick, $email);
            unset($auth);

            if ($result) {
                $user->Nick = $nick;
                $user->FirstName = $first_name;
                $user->LastName = $last_name;
                $user->Email = $email;
            }

            die(json_encode(['success' => true]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function ActionChangePassword(): never
    {
        $pass = $_POST['pass'];
        $new_pass = $_POST['new-pass'];
        $re_pass = $_POST['re-pass'];

        //Verificando el formato de las nuevas contraseñas.
        if (!Regex::Match(AppRegex::UserPass, $pass))
            die(Json::GetJsonWarning(1, 'La contraseña actual no posee la longitud suficiente o tiene caracteres no validos.'));
        else if (!Regex::Match(AppRegex::UserPass, $new_pass))
            die(Json::GetJsonWarning(2, 'La nueva contraseña no posee la longitud suficiente o tiene caracteres no validos.'));
        else if ($pass == $new_pass)
            die(Json::GetJsonWarning(3, 'La nueva contraseña no puede ser igual a la actual.'));
        else if ($new_pass !== $re_pass)
            die(Json::GetJsonWarning(4, 'Las nuevas contraseñas especificadas no coinciden entre si.'));

        //Verificando si la contraseña actual es correcta.
        try {
            $user_id = Session::GetOnlyRead()->User->ID;

            //Obteniendo contraseña actual.
            $auth = new AuthModel();
            $hashed_pass = $auth->GetCurrentPassword($user_id);

            //Verificando que la contraseña actual es correcta.
            if (!password_verify($pass, $hashed_pass)) {
                sleep(2);
                die(Json::GetJsonWarning(5, 'La contraseña actual no es correcta.'));
            }

            //Actualizando contraseña en la base de datos.
            if (!$auth->ChangePassword($user_id, HashCrypt::Hash($new_pass)))
                die(Json::GetJsonError(2, 'La contraseña no pudo ser actualizada.'));
            else
                die(json_encode(['success' => true]));
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }

    public function ViewFavorites(): never
    {
        try {
            $model = new FavoriteModel();
            $favorites = $model->GetAll(Session::GetOnlyRead()->User->ID);
            unset($model);
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }

        View::GetClientData()->Favorites = $favorites;

        View::GetLayout()
            ->SetTitle('Mis favoritos')
            ->AddStyle(['pages/settings', 'layout/listing-layout'])
            ->AddScript('pages/account')
            ->AddSection('main', '/user/favorites');

        Template::CallClient('template');
    }

    public function ActionAddFavorite(): never
    {
        $this->ActionFavorites(true);
        die;
    }

    public function ActionRemoveFavorite(): never
    {
        $this->ActionFavorites(false);
        die;
    }

    private function ActionFavorites(bool $add): void
    {
        $project = $_POST['novel'];

        try {
            //Obteniendo ID del proyecto.
            $model = new NovelModel();
            $project_id = $model->GetID($project);
            unset($model);

            //Verificando que exista.
            if (is_null($project_id)) {
                Http::SetResponseCode(HttpStatus::C404_NOTFOUND);
                die;
            }

            //Agregando a favoritos.
            $model = new UserModel();
            if ($add)
                $model->AddFavorite(Session::GetOnlyRead()->User->ID, $project_id);
            else
                $model->RemoveFavorite(Session::GetOnlyRead()->User->ID, $project_id);
            unset($model);

            die(Json::GetJsonSuccess());
        } catch (PDOException $ex) {
            Logger::SetInternalErrorAndLog(LogFile::DB, $ex);
        }
    }
}