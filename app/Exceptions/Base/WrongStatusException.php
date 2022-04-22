<?php

namespace App\Exceptions\Base;

use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class WrongStatusException extends InvalidArgumentException implements HttpExceptionInterface
{
    public function __construct()
    {
        parent::__construct(__('Неверный статус'));
    }

    public function getStatusCode(): int
    {
        return 400;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
