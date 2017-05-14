<?php

namespace App\Services;


class QRcodeService
{
    public static function generateQRcode($text = '', $size = 50, $margin = 2)
    {
        require 'vendors/phpqrcode.php';
        \QRcode::png($text,false,QR_ECLEVEL_L,$size/25,$margin);
    }
}