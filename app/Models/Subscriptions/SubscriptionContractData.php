<?php

namespace App\Models\Subscriptions;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $subscription_contract_id
 *
 * @property string $lastname
 * @property string $firstname
 * @property string $patronymic
 * @property string $phone
 * @property string $email
 * @property string $passport_serial
 * @property string $passport_number
 * @property string $passport_place
 * @property Carbon $passport_date
 * @property string $passport_code
 * @property string $registration_address
 * @property string $ward_lastname
 * @property string $ward_firstname
 * @property string $ward_patronymic
 * @property Carbon $ward_birth_date
 * @property string $ward_document
 * @property Carbon $ward_document_date
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SubscriptionContractData extends Model
{
    /** @var array Attribute casting */
    protected $casts = [
        'passport_date' => 'datetime',
        'ward_birth_date' => 'datetime',
        'ward_document_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
