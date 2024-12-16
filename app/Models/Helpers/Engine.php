<?php

namespace App\Models\Helpers;

class Engine
{
    public static function generate_ucode($prefix): string {
        $ucode = $prefix."-".self::random_digit().date("YmdHis");
        return $ucode;
    }

    protected static function random_digit($length = 5) {
        // $result = "";
        // for ($i = 0; $i < $length; $i++) {
        //     $result .= random_int(0, 9);
        // }
        return str_pad(random_int(1000, pow(10, $length)-1), $length, '0', STR_PAD_LEFT);
    }
}
