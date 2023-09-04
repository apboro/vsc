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
 * @property string|null $header_of_contract
 * @property string|null $organization_inn
 * @property string|null $organization_kpp
 * @property string|null $organization_ogrn
 * @property string|null $bank_account
 * @property string|null $bank_title
 * @property string|null $bank_bik
 * @property string|null $bank_ks
 * @property string|null $legal_address
 * @property string|null $email
 * @property string|null $web_site
 * @property string|null $phone
 * @property string|null $sign
 */
class OrganizationRequisites extends AbstractDictionary
{
    /** @var string Referenced table name. */
    protected $table = 'dictionary_organization_requisites';

    /** @var bool Is bound to organization */
    protected static bool $organizationBound = true;
}
