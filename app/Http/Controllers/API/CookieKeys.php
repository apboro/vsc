<?php

namespace App\Http\Controllers\API;

class CookieKeys
{
    public const staff_list = 'staff_list';
    public const roles_list = 'roles_list';
    public const training_base_list = 'training_base_list';
    public const training_base_contracts_list = 'training_base_contracts_list';
    public const services_list = 'services_list';

    public static function getKey(string $key, ?int $organizationId = null): string
    {
        return $organizationId ? "{$key}_$organizationId" : $key;
    }
}
