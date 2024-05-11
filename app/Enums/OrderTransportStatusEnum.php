<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderTransportStatusEnum extends Enum
{
    public const DANG_GIAO = 4;
    public const DA_GIAO = 5;


    public static function getKeyByValue($value): string
    {
        return array_search($value, self::getArrayView(), true);
    }

    public static function getArrayView(): array
    {
        return [
            'Đang giao' => self::DANG_GIAO,
            'Đã giao' => self::DA_GIAO,
        ];
    }
}
