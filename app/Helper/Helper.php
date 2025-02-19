<?php

namespace App\Helper;
use NumberFormatter;

class Helper
{

    public static function numberToWord($num) {
        list($whole, $decimal) = explode('.', $num);
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $final = $f->format((int)$whole) . ' Pesos & ' .$decimal. '/100 Only';
            return ucwords($final);
        }
}

?>