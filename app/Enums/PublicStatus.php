<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PublicStatus extends Enum
{
    const CLOSE = 0;
    const OPEN = 1;

    // public static function getDescription($value): string
    // {
    //     if ($value === self::CLOSE) {
    //         return '非公開';
    //     }

    //     if ($value === self::OPEN) {
    //         return '公開';
    //     }

    //     return parent::getDescription($value);
    // }

    // public static function getValue(string $key)
    // {
    //     if ($key === '非公開') {
    //         return self::CLOSE;
    //     }

    //     if ($key === '公開') {
    //         return self::OPEN;
    //     }

    //     return parent::getValue($key);
    // }
}
