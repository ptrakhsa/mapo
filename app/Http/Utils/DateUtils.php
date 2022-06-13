<?php

namespace App\Http\Utils;

class DateUtils
{
    public static function parseRange($start, $end)
    {
        $startYear = date('Y', strtotime($start));
        $endYear = date('Y', strtotime($end));

        // expected output 
        // case 1 di hari yang sama cuma beda waktu
        // s : 1 maret 2020 9:30 
        // e : 1 maret 2020 10:40
        // o : 1 maret 2020 9:30 - 10:40

        // case 2 di hari yang berbeda 
        // s : 1 maret 2020 9:30 
        // e : 2 maret 2020 10:40
        // o : 1 - 2 maret 2020 9:30 - 10:40


        return [$startYear, $endYear];
    }
}
