<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AppointmentStatusEnum extends Enum
{
    public const CHO_XAC_NHAN = 0;
    public const XAC_NHAN = 1;
    public const TU_CHOI = 2;
    public const KHACH_HANG_HUY = 3;


    public static function getKeyByValue($value): string
    {
        return array_search($value, self::getArrayView(), true);
    }

    public static function getArrayView(): array
    {
        return [
            'Chờ xác nhận' => self::CHO_XAC_NHAN,
            'Xác nhận' => self::XAC_NHAN,
            'Từ chối' => self::TU_CHOI,
            'Khách hàng đã hủy' => self::KHACH_HANG_HUY,
        ];
    }
}
