<?php

namespace App\Enums;

enum Roles: string
{
    case SUPERADMIN = 'superadmin';

    /**
     * Get all order status payment
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
