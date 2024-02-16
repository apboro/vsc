<?php

namespace App\Models\Subscriptions;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $subscription_contract_id
 *
 * @property string|null $organization_name
 * @property string|null $in_face
 * @property string|null $inn
 * @property string|null $kpp
 * @property string|null $checking_account
 * @property string|null $bic
 * @property string|null $corr_account
 * @property string|null $org_email
 * @property string|null $org_phone
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SubscriptionContractOrganizationData extends Model
{
    /** @var string The primary key associated with the table. */
    protected $primaryKey = 'subscription_contract_id';

    /** @var bool Disable auto-incrementing on model. */
    public $incrementing = false;

    /** @var array Attribute casting */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
