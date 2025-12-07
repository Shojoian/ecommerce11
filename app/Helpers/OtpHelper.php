<?php

namespace App\Helpers;

class OtpHelper
{
    public static function generate()
    {
        return rand(100000, 999999);
    }
}
