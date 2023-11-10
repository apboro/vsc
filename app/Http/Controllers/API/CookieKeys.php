<?php

namespace App\Http\Controllers\API;

class CookieKeys
{
    public const staff_list = 'staff_list';
    public const roles_list = 'roles_list';
    public const training_base_list = 'training_base_list';
    public const training_base_contracts_list = 'training_base_contracts_list';
    public const services_list = 'services_list';
    public const leads_list = 'leads_list';
    public const clients_list = 'clients_list';
    public const clients_wards_list = 'clients_wards_list';
    public const clients_comments_list = 'clients_comments_list';
    public const subscriptions_list = 'subscriptions_list';
    public const subscriptions_documents_list = 'subscriptions_documents_list';
    public const transactions_list = 'transactions_list';
    public const invoices_list = 'invoices_list';

    public static function getKey(string $key, ?int $organizationId = null): string
    {
        return $organizationId ? "{$key}_$organizationId" : $key;
    }
}
