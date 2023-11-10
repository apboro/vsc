<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class InvoiceStatus extends AbstractDictionary
{
    /** @var int Счет создан */
    public const draft = 1;

    /** @var int Счет ожидает даты отправки */
    public const ready = 2;

    /** @var int Отправлен*/
    public const sent = 3;

    /** @var int Оплачен */
    public const paid = 4;

    /** @var int Аннулирован */
    public const cancelled = 5;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_invoice_statuses';
}
