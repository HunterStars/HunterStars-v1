<?php

namespace HS\libs\core;

use HS\config\enums\AppDirs;
use HS\config\enums\AppRegex;
use HS\libs\helpers\MimeType;
use HS\libs\helpers\Regex;
use HS\libs\io\Path;
use HS\libs\io\Url;
use HS\libs\net\HttpMethod;
use HS\libs\net\HttpResponse;
use http\Exception\InvalidArgumentException;
use const HS\APP_NAMESPACE;
use const HS\APP_PATH;
use const HS\APP_URL;

class RouteItem
{
    public HttpMethod $Method;
    public string $RouteUrl;
    public mixed $Callback;
    public array $UrlConditions;
    public ?array $MethodConditions;
    public ?MimeType $ContentType;
    public bool $ExitAfterMatch;

    public function __construct(HttpMethod $method, string $route_url, string|callable $callback, array $url_conditions = [], ?array $method_conditions = [], ?MimeType $content_type = null, bool $exitAfterMatch = true)
    {
        $this->Method = $method;
        $this->RouteUrl = $route_url;
        $this->Callback = $callback;
        $this->UrlConditions = $url_conditions;
        $this->MethodConditions = $method_conditions;
        $this->ContentType = $content_type;
        $this->ExitAfterMatch = $exitAfterMatch;
    }
}

class Route
{
    const GLOBAL_GET_PARAMS = [
        'gtm_debug' => '^\d+$',
        '_gl' => '^.+$'
    ];

    /**
     * @param RouteItem[] $routes_list
     */
    public static function Section(string $base_route_url, array $routes_list, bool $loginRequired = false): void
    {
        if ($loginRequired)
            Session::IfNoLoginRedirect();

        foreach ($routes_list as $route) {
            self::Run(Url::Combine($base_route_url, $route->RouteUrl), $route->Callback, [$route->Method->value], $route->UrlConditions, $route->MethodConditions, $route->ContentType, $route->ExitAfterMatch);
        }
    }


    public static function All(string $route_url, $url_or_call, array $conditions = [], bool $exitAfterMatch = true): void
    {
        self::Run($route_url, $url_or_call, ['GET', 'POST'], $conditions, [], null, $exitAfterMatch);
    }

    public static function Get(string $route_url, $url_or_call, array $conditions = [], ?array $method_conditions = [], ?MimeType $ContentType = null, bool $exitAfterMatch = true): void
    {
        self::Run($route_url, $url_or_call, ['GET'], $conditions, $method_conditions, $ContentType, $exitAfterMatch);
    }

    public static function Post(string $route_url, $url_or_call, array $conditions = [], ?array $method_conditions = [], ?MimeType $ContentType = null, bool $exitAfterMatch = true): void
    {
        self::Run($route_url, $url_or_call, ['POST'], $conditions, $method_conditions, $ContentType, $exitAfterMatch);
    }

    private static function Run(string $route_url, $url_or_call, array $request_methods, array $conditions, ?array $method_conditions, ?MimeType $ContentType, bool $exitAfter): void
    {
        //Verificando si no es un tipo de petición permitido.
        if (!in_array($_SERVER['REQUEST_METHOD'], $request_methods, true))
            return;

        //Arreglando urls.
        $request_url = $_SERVER['REQUEST_METHOD'] == 'GET' ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : $_SERVER['REQUEST_URI'];
        $request_url = Url::Trim(urldecode($request_url));
        $route_url = URL::Trim($route_url);

        //Eliminando la parte raíz de la RequestUrl.
        if (str_starts_with($request_url, APP_URL))
            $request_url = substr($request_url, strlen(APP_URL));

        if (str_starts_with($route_url, APP_URL))
            $route_url = substr($route_url, strlen(APP_URL));

        //Extrayendo nombres de variables de la ruta. Ej: {Var1} ó {Var1*}
        preg_match_all('#\{(\w+|\w+\+|\w+\*)}#', $route_url, $varsName);
        $varsName = $varsName[1]; //0: Nombres con llaves | 1: Nombres sin llaves.

        //Si no había variable en la ruta, llamar la vista.
        if (empty($varsName) && $request_url == $route_url && self::VerifyGetPostParams($method_conditions))
            self::CallView($url_or_call, [], $ContentType, $exitAfter);

        //Establecer regex genérico para obtener luego los valores de las variables.
        $route_url = preg_replace(['#\{\w+}#', '#\{\w+\\\\\+}#', '#\{\w+\\\\\*}#'], ["([^/]+)", "(.+)", "(.*)"], Regex::Escape($route_url, '{}')); //Palabras o numeros

        //Comprobar si la URL actual tiene la misma estructura que la ruta y obteniendo valores de variables.
        if (preg_match("#^$route_url$#", $request_url, $varsValue)) {
            //Eliminando url completa del match.
            unset($varsValue[0]);

            //Comprobando condiciones para cada variable de la url, si las hubiera.
            if (!empty($conditions)) {
                foreach ($varsName as $index => $name) {
                    $value = $varsValue[$index + 1];

                    //Eliminando caracteres inválidos en nombres de condiciones de la url.
                    $name = str_replace(['*', '+'], "", $name);

                    //Si existe una condición que comprobar...
                    if (!empty($conditions[$name])) {
                        $cond = $conditions[$name];

                        //Comprobando condición.
                        if (!self::VerifyParam($cond, $value))
                            return;

                        //Eliminando condiciones comprobadas
                        unset($conditions[$name]);
                    }
                }
            }

            //Una vez que la url haya sido verificada.
            //Crear un arreglo con el nombre y el contenido de las variables de la ruta.
            //Y agregando las variables condicionales sobrantes.
            $VARS = array_merge(...array_map(fn($name, $value) => [str_replace(['+', '*'], "", $name) => $value], $varsName, $varsValue));
            if (!empty($conditions)) $VARS = array_merge($VARS, $conditions);

            //Comprobando condiciones en variables pasadas por GET y POST.
            if (self::VerifyGetPostParams($method_conditions)) {
                //Llamando a callback, y pasándole un objeto con las variables de la URL.
                self::CallView($url_or_call, $VARS, $ContentType, $exitAfter);
            }
        }
    }

    private static function VerifyGetPostParams(?array $method_params): bool
    {
        if (is_null($method_params)) {
            $_GET = array_map(fn($value) => trim($value), $_GET);
            $_POST = array_map(fn($value) => trim($value), $_POST);

            return true;
        }

        $filter_trim = function (array $data) use (&$method_params): array {
            $data = array_map(fn($value) => trim($value), $data);
            return array_filter($data, fn($value, $key) => (isset($method_params[$key]) || isset($method_params["¿$key?"])) and !is_null($value), ARRAY_FILTER_USE_BOTH);
        };

        $challenges = function (array $data, array $global_param) use (&$method_params): bool {
            foreach ($method_params as $key => $challenge) {
                //Quitando los caracteres "¿?" de la condición.
                if (str_starts_with($key, '¿') && str_ends_with($key, '?'))
                    $key = mb_substr($key, 1, mb_strlen($key) - 2);

                //Si existe y no es null.
                if (isset($data[$key])) {
                    if (!self::VerifyParam($challenge, $data[$key]))
                        return false;
                } //Si no se pasó el valor y es un parámetro opcional de los globales.
                else if ((isset($global_param[$key]) || isset($method_params["¿$key?"])))
                    continue;
                else
                    return false;
            }

            return true;
        };

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            //Tomando en consideración los parámetros globales permitidos.
            $method_params = array_merge($method_params, self::GLOBAL_GET_PARAMS);

            //Conservando solo parámetros get condicionados.
            $temp_get = $filter_trim($_GET);
            $_POST = [];

            if (count($temp_get) != count($_GET))
                return false;

            //Recorriendo condiciones.
            return $challenges($_GET = $temp_get, self::GLOBAL_GET_PARAMS);
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Conservando solo parámetros post condicionados.
            $temp_post = $filter_trim($_POST);
            $_GET = [];

            if (count($temp_post) != count($_POST))
                return false;

            //Recorriendo condiciones.
            return $challenges($_POST = $temp_post, []);
        } else
            return true;
    }

    private static function VerifyParam(string|callable|array|AppRegex $cond, string $value): bool
    {
        //$cond es una expresión regular.
        if (is_string($cond)) {
            return preg_match("#$cond#", $value) === 1;
        } else if (is_a($cond, AppRegex::class)) {
            return preg_match("#$cond->value#", $value) === 1;
        } //$cond es una función.
        else if (is_callable($cond)) {
            return $cond($value);
        } //$cond es un array de valores.
        else if (is_array($cond)) {
            return in_array($value, $cond, true);
        } else
            //$cond es de un tipo no valido.
            throw new \InvalidArgumentException("Una de las condiciones para la url es de un tipo no valido.");
    }

    private static function CallView($callback, array $args, ?MimeType $ContentType, bool $exitAfter): void
    {
        if (is_callable($callback))
            call_user_func_array($callback, $args);
        else if (is_string($callback)) {
            if (!preg_match("/^(.+)#(.+)$/", $callback, $parts)) {
                define(__NAMESPACE__ . "\URL_VARS", $args);
                unset($args);
                require Path::CombineRoot(AppDirs::VIEW, $callback); //Ruta de un archivo.
            } else {
                //Instanciando clase del controlador.
                if (class_exists($parts[1], true)) {
                    $class = new $parts[1]();
                }
                //Si no se encontró la clase, buscarla en los archivos incluidos,
                //e instanciarla con su nombre completamente cualificado.
                else {
                    $class = self::ResolveFQN($parts[1]);
                    $class = new $class();

                    //TODO Si no se encontró aun asi, pues... ya valimos.
                }

                //Llamando método de la clase encontrada.
                if (!is_null($ContentType)) {
                    Session::GetOnlyRead();
                    HttpResponse::SetContentType($ContentType);
                }

                $result = call_user_func_array([$class, $parts[2]], $args);
                if (is_string($result))
                    echo $result;
            }
        } else
            throw new InvalidArgumentException("El callback especificado es de un tipo no valido.");

        //Si se requiere terminar la ejecución después de mostrar la vista o ejecutar la función $callback.
        if ($exitAfter) exit(0);
    }

    private static function ResolveFQN(string $class): string
    {
        if (str_starts_with($class, '\\'))
            return $class;
        else {
            foreach (get_required_files() as $file) {
                if (pathinfo($file)['filename'] == $class) {
                    $namespace = str_replace('/', '\\', $file);
                    $namespace = substr_replace($namespace, APP_NAMESPACE, 0, strlen(APP_PATH));

                    return substr($namespace, 0, strlen($namespace) - 4);
                }
            }

            return $class;
        }
    }
}