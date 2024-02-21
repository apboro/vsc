<?php

namespace App\Models\Dictionaries;

class Bank extends AbstractDictionary
{
    protected $table = 'dictionary_banks';
    public const SBERBANK = 1;
    public const TINKOFF = 5;
    public const TOCHKA = 10;
}
