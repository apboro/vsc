<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\CookieKeys;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        'vsc_organization',
        CookieKeys::staff_list,
        CookieKeys::roles_list,
        CookieKeys::training_base_list,
        CookieKeys::training_base_contracts_list,
    ];
}
