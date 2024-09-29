<?php

namespace App\Services;

use Faker\Factory;
use Faker\Provider\Base;

class FakerImage extends Base
{
    public static function image(string $dir = null, int $width = 1920, int $height = 1080, string $imageExtension = 'jpg'): string
    {    
        list($r, $g, $b) = self::getBackgroundColor();
        $image = imagecreate($width, $height);
        $background = imagecolorallocate($image, $r, $g, $b);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        // set text and center text
        $text = self::getText();
        resource_path('fonts/Lemon_Milk_Pro_Regular.otf');

        $font = resource_path('fonts/Lemon_Milk_Pro_Regular.otf');
        $size = 30;
        $box = imagettfbbox($size, 0, $font, $text);
        $textWidth = abs($box[2]) - abs($box[0]);
        $textHeight = abs($box[5]) - abs($box[3]);
        $x = round(($width - $textWidth) / 2, 0);
        $y = round(($height + $textHeight) / 2, 0);
        imagettftext($image, $size, 0, $x, $y, $textColor, $font, $text);

        imagecolordeallocate($image, $textColor);
        imagecolordeallocate($image, $background);

        $dir = $dir === null ? sys_get_temp_dir() : $dir;
        $name = md5(uniqid().time().random_int(10000, 99999));
        $filename = $name . "." . $imageExtension;
        $filePath = $dir . DIRECTORY_SEPARATOR . $filename;
        imagejpeg($image, $filePath);
        
        return $filePath;
    }

    public static function getText()
    {
        $faker = Factory::create();
        return $faker->word();
    }

    public static function getBackgroundColor()
    {
        return [rand(0,255), rand(0,255), rand(0,255)];
    }
}