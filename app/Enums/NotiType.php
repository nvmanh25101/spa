<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;


final class NotiType extends Enum
{
    public const LICH = 0;
    public const DON_HANG = 1;

    public static function getKeyByValue($value): string
    {
        return array_search($value, self::getArrayView(), true);
    }

    public static function getArrayView(): array
    {
        return [
            'Lịch' => self::LICH,
            'Đơn hàng' => self::DON_HANG,
        ];
    }
}
