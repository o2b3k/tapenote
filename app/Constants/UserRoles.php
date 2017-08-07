<?php

namespace App\Constants;


class UserRoles
{
    const ROLE_SUPER_USER = 'SUPER_ADMIN';
    const ROLE_ADMIN = 'ADMIN';

    public static function getRoles() {
        return [
            'Super user' => self::ROLE_SUPER_USER,
            'Administrator' => self::ROLE_ADMIN,
        ];
    }
}