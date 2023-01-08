<?php

namespace App\Enums;

enum Permissions: string
{
    case MANAGE_INVENTORY = 'manage inventory';

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
