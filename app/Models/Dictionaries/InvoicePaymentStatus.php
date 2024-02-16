<?php

namespace App\Models\Dictionaries;

use App\Models\Dictionaries\AbstractDictionary;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class InvoicePaymentStatus extends AbstractDictionary
{
    /** @var int Не оплачен */
    public const unpaid = 1;

    /** @var int Частично оплачен */
    public const partially_paid = 2;

    /** @var int Оплачен */
    public const paid = 3;

    /** @var int платёж создан */
    public const created = 10;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_invoice_payment_statuses';
}
