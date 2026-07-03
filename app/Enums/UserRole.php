<?php

namespace App\Enums;

enum UserRole: string
{
    case Owner = 'owner';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Owner',
            self::Admin => 'Admin/Kasir',
        };
    }
}
