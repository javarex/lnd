<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasDateFormat
{

    public static function shortDate(mixed $date, $format='F d, Y'): string
    {
        return Carbon::parse($date)->format($format); // This will return (e.g Oct 25, 23) 
    }

    public static function longDate(mixed $date, $format='F d, Y'): string
    {
        return Carbon::parse($date)->format($format); // This will return (e.g Oct 25, 2023) 
    }

    public static function date_range_format(mixed $date1, mixed $date2, $format='F d, Y'): string
    {
        if ($date1 == $date2) {
            return Self::longDate($date1, $format);
        } else {
            return Self::longDate($date1, $format) . " - " . Self::longDate($date2, $format);
        }
    }
}
