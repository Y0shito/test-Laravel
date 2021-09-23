<?php

namespace App\Traits;

trait Spaceremoval
{
    public static function spaceRemoval($value)
    {
        // 文字列の前後にある空白、改行等の削除
        $pattern = '/\A[\p{Cc}\p{Cf}\p{Z}]++|[\p{Cc}\p{Cf}\p{Z}]++\z/u';

        $formatted_value = preg_replace($pattern, '', $value);
        return $formatted_value;
    }
}
