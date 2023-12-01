<?php

namespace App\Enums;

enum RoleEnum: string
{
    case BUYER = 'buyer';
    case SELLER = 'seller';

    public function isBuyer(): bool
    {
        return $this === self::BUYER;
    }

    public function isSeller(): bool
    {
        return $this === self::SELLER;
    }
}
