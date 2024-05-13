<?php

namespace HS\libs\helpers;

use Exception;
use HS\config\enums\AppDirs;
use HS\config\LogFile;
use HS\libs\io\Path;
use HS\libs\net\Http;
use HS\libs\net\HttpResponse;
use HS\libs\net\HttpStatus;
use const HS\APP_PATH;

class Logger
{
    private static function Write(LogFile|string $filename, string $title, Exception|string $message): void
    {
        if (is_a($filename, LogFile::class))
            $filename = $filename->value;

        //Obteniendo ruta completa del fichero log.
        $filename = Path::CombineRoot(AppDirs::LOG, "$filename.log");

        //Obtener código de respuesta.
        $message = http_response_code() . " | $title | $message\n";

        //Creando archivo de log.
        if (IOUtils::CreateFile($filename)) {
            if (error_log($message, 3, $filename))
                return;
        }

        //Si no se pudo escribir los logs en el archivo especificado...
        error_log($message);
    }

    public static function WriteString(LogFile $filename, string $error, int $traceOffset = 1): void
    {
        $ex = (new Exception())->getTrace()[$traceOffset];

        self::Write($filename, "{$ex['class']}{$ex['type']}{$ex['function']}", $error);
    }

    public static function WriteException(LogFile $filename, Exception $error, int $traceOffset = 1): void
    {
        $ex = (new Exception())->getTrace()[$traceOffset];
        $trace = $error->getTrace()[0];

        self::Write($filename, "{$ex['class']}{$ex['type']}{$ex['function']}",
            sprintf("%s%s%s(%s)\n%s\n%s",
                $trace['class'], $trace['type'], $trace['function'], var_export($trace['args'], true),
                $error->getMessage(),
                $error->getTraceAsString())
        );
    }

    public static function SetInternalErrorAndLog(LogFile $filename, Exception $error): never
    {
        Http::SetResponseCode(HttpStatus::C500_INTERNAL_ERROR);
        self::WriteException($filename, $error, 2);
        die;
    }
}