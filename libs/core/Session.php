<?php

namespace HS\libs\core;

use DateInterval;
use DateTime;
use Exception;
use HS\app\models\UserModel;
use HS\config\enums\SubDomains;
use HS\libs\exception\PropertyNotFoundException;
use HS\libs\net\HttpResponse;
use const HS\APP_DEBUG;
use const HS\config\APP_SESSION_NAME;

/**
 * @property UserModel $User
 * @property string $Agent
 * @property string $IP
 * @property string $TrackID
 */
class Session
{
    private bool $OnlyRead;

    private function __construct($onlyRead)
    {
        $this->OnlyRead = $onlyRead;

        if ($onlyRead) {
            $this->Start();
            $this->Close();
        }
    }

    public static function Get(): Session
    {
        return new Session(false);
    }

    public static function GetOnlyRead(): Session
    {
        return new Session(true);
    }

    #Metodos mágicos.

    /**
     * @throws Exception Lanza una Exception si se accede a una propiedad inexistente.
     */
    public function __get(string $key)
    {
        if (!$this->OnlyRead)
            $this->Start();

        if (!isset($_SESSION[$key]))
            throw new PropertyNotFoundException("Propiedad \"$key\" no definida.");

        return $_SESSION[$key];
    }

    /**
     * @throws Exception Lanza una Exception si la sesión está en solo lectura.
     */
    public function __set(string $key, mixed $value): void
    {
        if ($this->OnlyRead) {
            throw new Exception("No se permite modificar la propiedad \"$key\"");
        }

        $this->Start();
        $_SESSION[$key] = $value;
    }

    public function __isset(string $key)
    {
        if (!$this->OnlyRead)
            $this->Start();
        
        return isset($_SESSION[$key]);
    }

    #Metodos públicos
    public function IsLogin(): bool
    {
        if (!$this->OnlyRead)
            $this->Start();

        /*TODO if (isset($_COOKIE['XDEBUG_SESSION']))
            return false;*/

        if (empty($this))
            return false;

        if (empty($this->IP) || $this->IP != $_SERVER['REMOTE_ADDR'])
            return false;

        if (empty($this->Agent) || $this->Agent != $_SERVER['HTTP_USER_AGENT'])
            return false;

        if (empty($this->User) || empty($this->User->ID) || empty($this->User->Nick)
            || !is_string($this->User->ID) || !is_string($this->User->Nick))
            return false;

        //Sesión iniciada.
        return true;
    }

    public function Close(): void
    {
        if (!$this->OnlyRead && $this->IsStart())
            session_write_close();
    }

    #Metodos privados.
    private function Start(): void
    {
        if (!$this->IsStart()) {
            //Iniciando sesión.
            session_start();

            //Verificando si hay que eliminar la sesión y denegar el acceso.
            if (!empty($_SESSION['k-time']) && $_SESSION['k-time'] < new DateTime()) {
                //Eliminando sesión.
                $this->Kill();

                //Recargando el sitio.
                HttpResponse::Redirect($_SERVER['REQUEST_URI']);
            }

            //Verificando si hay que regenerar la sesión.
            if (empty($_SESSION['sv-time']) || $_SESSION['sv-time'] < new DateTime()) {
                //Estableciendo tiempo de eliminación de la sesión antigua.
                $_SESSION['k-time'] = date_add(new DateTime(), new DateInterval("PT1M"));

                //Regenerando sesión y estableciendo tiempo de próxima regeneración y tiempo de eliminación.
                session_regenerate_id();
                $_SESSION['sv-time'] = date_add(new DateTime(), new DateInterval("PT5M"));
                $_SESSION['k-time'] = date_add(new DateTime(), new DateInterval((APP_DEBUG ? "PT200M" : "PT20M")));
            }
        }
    }

    private function IsStart(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function Kill(): void
    {
        //Abriendo sesión.
        $this->Start();

        //Eliminando variables de sesión;
        $_SESSION = array();
        session_unset();

        //Eliminando Cookies de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        //Destruyendo la sesión
        session_destroy();
    }

    public static function IfNoLoginRedirect(bool $closeSession = false): void
    {
        $session = Session::Get();
        if (!$session->IsLogin() && $_SERVER['REQUEST_URI'] != '/login') {
            //$url = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
            //$url .= SubDomains::root->value . '/login';
            HttpResponse::Redirect('/login');
        }

        if ($closeSession)
            $session->Close();
    }
}