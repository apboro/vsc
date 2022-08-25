<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 * @property int $organization_id
 *
 * @property string|null $organization_title
 * @property string|null $organization_inn
 * @property string|null $organization_kpp
 * @property string|null $bank_account
 * @property string|null $bank_title
 * @property string|null $bank_bik
 * @property string|null $bank_ks
 */
class OrganizationRequisites extends AbstractDictionary
{
    /** @var string Referenced table name. */
    protected $table = 'dictionary_organization_requisites';

    /** @var bool Is bound to organization */
    protected static bool $organizationBound = true;
}
