<?php

namespace App\Exceptions;

use Exception;

class PayKeeperGetException extends Exception
{

    public function __construct()
    {
        parent::__construct('Нет связи с сервером');
    }
}
