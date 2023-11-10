<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class InvoiceType extends AbstractDictionary
{
    /** @var int Базовый счет */
    public const base = 1;

    /** @var int Перерасчет */
    public const recalculation = 2;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_invoice_types';
}
