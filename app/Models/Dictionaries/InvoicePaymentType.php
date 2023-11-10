<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class InvoicePaymentType extends AbstractDictionary
{
    /** @var int Эквайринг */
    public const acquiring = 1;

    /** @var int Наличный расчет */
    public const cash = 2;

    /** @var int По реквизитам в банке */
    public const bank_requisites = 3;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_invoice_payment_types';
}
