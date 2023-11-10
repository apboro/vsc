<?php

namespace App\Models\Invoices;

use App\Helpers\PriceConverter;
use App\Mail\InvoiceMail;
use App\Models\Account\AccountTransaction;
use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Dictionaries\InvoiceType;
use App\Models\Subscriptions\SubscriptionContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

/**
 * @property int $id
 *
 * @property string|null $comment
 *
 * @property Carbon $date_from
 * @property Carbon $date_to
 *
 * @property bool $moderation_required
 *
 * @property int $amount_to_pay
 * @property int|null $amount_paid
 *
 * @property Carbon|null $paid_at
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property int $contract_id
 * @property int $status_id
 * @property int $type_id
 * @property int|null $payment_type_id
 *
 * @property-read SubscriptionContract $contract
 * @property-read InvoiceStatus $status
 * @property-read InvoiceType $type
 * @property-read InvoicePaymentType|null $paymentType
 * @property-read Collection<AccountTransaction> $transactions
 */
class Invoice extends Model
{
    protected $fillable = [
        'date_from', 'date_to', 'moderation_required',
        'amount_to_pay', 'amount_paid', 'paid_at',
        'contract_id', 'status_id', 'type_id', 'payment_type_id',
        'comment',
    ];

    protected $dates = [
        'date_from', 'date_to', 'paid_at'
    ];

    public function sendToClient()
    {
        Mail::send(new InvoiceMail($this));
    }

    /**
     * Convert amount to store value.
     *
     * @param float $value
     *
     * @return  void
     */
    public function setAmountToPayAttribute(float $value): void
    {
        $this->attributes['amount_to_pay'] = PriceConverter::priceToStore($value);
    }

    /**
     * Convert amount from store value to real currency.
     *
     * @param int $value
     *
     * @return  float
     */
    public function getAmountToPayAttribute(int $value): float
    {
        return PriceConverter::storeToPrice($value);
    }

    /**
     * Convert amount to store value.
     *
     * @param float $value
     *
     * @return  void
     */
    public function setAmountPaidAttribute(float $value): void
    {
        $this->attributes['amount_paid'] = PriceConverter::priceToStore($value);
    }

    /**
     * Convert amount from store value to real currency.
     *
     * @param int|null $value
     *
     * @return  float|null
     */
    public function getAmountPaidAttribute(?int $value): ?float
    {
        return $value ? PriceConverter::storeToPrice($value) : null;
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(SubscriptionContract::class, 'contract_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(InvoiceType::class, 'type_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(InvoiceStatus::class, 'status_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(InvoicePaymentType::class, 'payment_type_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AccountTransaction::class, 'invoice_id', 'id');
    }
}
