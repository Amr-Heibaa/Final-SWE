<?php

namespace App\Enums;

enum RoleEnum: string
{
    case CUSTOMER     = 'customer';
    case ADMIN        = 'admin';
    case ADMIN_MANAGER = 'admin_manager';
    case SUPER_ADMIN  = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::CUSTOMER      => 'Customer',
            self::ADMIN         => 'Admin',
            self::ADMIN_MANAGER => 'Admin Manager',
            self::SUPER_ADMIN   => 'Super Admin',
        };
    }
}
