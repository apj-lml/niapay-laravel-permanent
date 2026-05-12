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

    public static function truncateString($number, $precision = 2) {
        // Convert to string to avoid floating point issues
        $number_string = (string)$number;
        
        // Find the position of the decimal point
        $decimal_point_index = strpos($number_string, '.');
        
        // If a decimal point exists, slice the string
        if ($decimal_point_index !== false) {
            // Keep everything up to the desired precision + 1 (for the dot itself)
            $truncated_string = substr($number_string, 0, $decimal_point_index + $precision + 1);
            return (float)$truncated_string; // Cast back to float if needed
        }
        
        return (float)$number_string; // Return original number as float if no decimal
    }
}

?>