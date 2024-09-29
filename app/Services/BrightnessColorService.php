<?php

namespace App\Services;

class BrightnessColorService
{
    /**
     * Increases or decreases the brightness of a color by a percentage of the current brightness.
     *
     * @param string $hex_color Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
     * @param float $brightness A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
     *
     * @return string
     *
     */
    public static function set_brightness(string $hex_color, float $brightness = -0.4): string {
        $hex_color = ltrim($hex_color, '#');
        
        if (strlen($hex_color) == 3) {
            $hex_color = $hex_color[0] . $hex_color[0] . $hex_color[1] . $hex_color[1] . $hex_color[2] . $hex_color[2];
        }
        
        $hex_color = array_map('hexdec', str_split($hex_color, 2));

        $new_color = [];
        foreach ($hex_color as $color) {
            if($color !== 0){
                $adjustable_limit = $brightness < 0 ? $color : 255 - $color;
                $adjust_amount = ceil($adjustable_limit * $brightness);
                $dec_hex = dechex($color + $adjust_amount);
                $new_color[] = strlen($dec_hex) === 1 ? str_pad($dec_hex, 2, '0', STR_PAD_LEFT) : $dec_hex;
            } else {
                $new_color[] = '00';
            }
        }

        return '#' . implode($new_color);
    }
}