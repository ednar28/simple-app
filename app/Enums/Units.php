<?php

namespace App\Enums;

enum Units: string
{
    case DOZEN = 'dozen';
    case GRAM = 'gram';
    case LITRES = 'litres';
    case ITEMS = 'items';

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
