<?php

namespace HS\libs\helpers;

use Cassandra\Date;
use DateInterval;
use DateTime;

class DateUtils
{
    const MONTH = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    public static function GetDate(string $date): string
    {
        return (new DateTime($date))->format('d/m/Y');
    }

    public static function GetLongDate(string $date): string
    {
        $date = new DateTime($date);
        $day = $date->format('d');
        $month = self::MONTH[$date->format('n') - 1];
        $year = $date->format('Y');
        return strtolower("$day de $month $year");
    }

    public static function GetDateTime(string $date): string
    {
        return (new DateTime($date))->format('d/m/Y h:i A');
    }

    public static function GetNaturalDate(string $date, bool $long = false): string
    {
        $current = new DateTime();
        $datetime = new DateTime($date);
        $current->setTime(0, 0);
        $datetime->setTime(0, 0);

        if ($current == $datetime)
            return 'Hoy';
        else if ($current->sub(new DateInterval('P1D')) == $datetime)
            return 'Ayer';
        else if ($long)
            return self::GetLongDate($date);
        else
            return self::GetDate($date);
    }

    public static function GetNaturalDateTime(string $date, bool $long = false): string
    {
        $current = new DateTime();
        //TODO
        //$current = $current->add(new DateInterval('PT6H')); //Diferencia horaria servidor.
        $datetime = new DateTime($date);
        $differences = $current->diff($datetime);
        $current->setTime(0, 0);
        $datetime->setTime(0, 0);

        if ($current == $datetime) {
            if ($differences->h == 1)
                return 'Hace 1 hora';
            else if ($differences->h > 1)
                return "Hace $differences->h horas";
            else if ($differences->i == 1)
                return 'Hace 1 minuto';
            else if ($differences->i > 1)
                return "Hace $differences->i minutos";
            else
                return "Hace $differences->s segundos";
        } else if ($current->sub(new DateInterval('P1D')) == $datetime)
            return 'Ayer';
        else if ($long)
            return self::GetLongDate($date);
        else
            return self::GetDate($date);
    }
}