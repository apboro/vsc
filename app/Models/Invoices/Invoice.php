<?php

namespace App\Models\Invoices;

use App\Helpers\PriceConverter;
use App\Mail\InvoiceMail;
use App\Models\Account\AccountTransaction;
use App\Models\Dictionaries\InvoicePaymentStatus;
use App\Models\Dictionaries\InvoicePaymentType;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Dictionaries\InvoiceType;
use App\Models\Payment;
use App\Models\Subscriptions\SubscriptionContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * @property int $id
 *
 * @property string|null $comment
 * @property string|null $delete_comment
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
 * @property int|null $payment_status_id
 *
 * @property int|null $trainings_attended
 * @property int|null $one_time_discount
 * @property int|null $recalc_method
 *
 * @property-read SubscriptionContract $contract
 * @property-read InvoiceStatus $status
 * @property-read InvoicePaymentStatus $paymentStatus
 * @property-read InvoiceType $type
 * @property-read InvoicePaymentType|null $paymentType
 * @property-read Collection<AccountTransaction> $transactions
 * @property-read Payment|null $payment
 */
class Invoice extends Model
{
    protected $fillable = [
        'date_from', 'date_to', 'moderation_required',
        'amount_to_pay', 'amount_paid', 'paid_at',
        'contract_id', 'status_id', 'type_id', 'payment_type_id',
        'comment', 'trainings_attended', 'one_time_discount', 'recalc_method',
        'payment_status_id', 'hash'
    ];

    protected $dates = [
        'date_from', 'date_to', 'paid_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(static function (self $approvement) {

            $hash = Str::random(16);

            while (Invoice::where('hash', $hash)->exists()) {
                $hash = Str::random(16);
            }
            $approvement->hash = $hash;
        });
    }

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
     * @param int|null $value
     *
     * @return  float|null
     */
    public function getAmountToPayAttribute(?int $value): ?float
    {
        if (!$value) {
            return null;
        }

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

    public function isEditable(): bool
    {
        return !in_array($this->status_id, [InvoiceStatus::sent, InvoiceStatus::paid, InvoiceStatus::cancelled]) &&
            $this->payment_status_id === InvoicePaymentStatus::unpaid;
    }

    public function isPayable(): bool
    {
        return !in_array($this->status_id, [InvoiceStatus::draft, InvoiceStatus::paid, InvoiceStatus::cancelled]) &&
            $this->payment_status_id !== InvoicePaymentStatus::paid;
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

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(InvoicePaymentStatus::class, 'payment_status_id', 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'invoice_id', 'id');
    }
}
