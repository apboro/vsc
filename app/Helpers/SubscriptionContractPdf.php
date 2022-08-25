<?php

namespace App\Helpers;

use App\Models\Subscriptions\SubscriptionContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class SubscriptionContractPdf
{
    /**
     * Generate ticket PDF.
     *
     * @param SubscriptionContract $contract
     *
     * @return  string|null
     */
    public static function generate(SubscriptionContract $contract): ?string
    {
        $contract->loadMissing([
            'contractData',
            'subscription',
            'discount',
            'subscription.service',
            'subscription.service.sportKind',
            'subscription.service.trainingBase.info',
        ]);

        $view = View::make('pdf/subscription_contract', [
            'contract_number' => $contract->number ?? '*не назначен*',
            'client_name' => $contract->contractData->lastname . ' ' . $contract->contractData->firstname . ' ' . $contract->contractData->patronymic,
            'contract_date' => self::formatDate($contract->start_at, 'г.'),
            'client_phone' => $contract->contractData->phone,
            'ward_name' => $contract->contractData->ward_lastname . ' ' . $contract->contractData->ward_firstname . ' ' . $contract->contractData->ward_patronymic,
            'ward_birth_date' => $contract->contractData->ward_birth_date->format('d.m.Y'),
            'trainings_per_week' => $contract->subscription->service->trainings_per_week,
            'trainings_per_month' => $contract->subscription->service->trainings_per_month,
            'training_duration' => $contract->subscription->service->training_duration,
            'sport_kind' => $contract->subscription->service->sportKind->name,
            'training_base_address' => $contract->subscription->service->trainingBase->info->address,
            'service_start_date' => self::formatDate($contract->subscription->service->start_at, 'года'),
            'service_end_date' => self::formatDate($contract->subscription->service->end_at, 'года'),
            'monthly_price' => $contract->subscription->service->monthly_price . ' руб.',
            'monthly_price_string' => PriceConverter::toString($contract->subscription->service->monthly_price),
            'training_return_price' => $contract->subscription->service->training_return_price . ' руб.',
            'training_return_price_string' => PriceConverter::toString($contract->subscription->service->training_return_price),
            'discount_1_monthly_price' => ($contract->subscription->service->monthly_price * 0.9) . ' руб.',
            'discount_1_monthly_price_string' => PriceConverter::toString(($contract->subscription->service->monthly_price * 0.9)),
            'discount_2_monthly_price' => ($contract->subscription->service->monthly_price * 0.9) . ' руб.',
            'discount_2_monthly_price_string' => PriceConverter::toString(($contract->subscription->service->monthly_price * 0.9)),
            'discount_3_monthly_price' => ($contract->subscription->service->monthly_price * 0.9) . ' руб.',
            'discount_3_monthly_price_string' => PriceConverter::toString(($contract->subscription->service->monthly_price * 0.9)),
            'client_email' => $contract->contractData->email,
            'client_address' => $contract->contractData->registration_address,
            'client_compact_name' => $contract->contractData->lastname . ' ' .
                mb_substr($contract->contractData->firstname, 0, 1) . '. ' .
                mb_substr($contract->contractData->patronymic, 0, 1) . '.',
            'client_passport_serial' => $contract->contractData->passport_serial,
            'client_passport_number' => $contract->contractData->passport_number,
            'client_passport_date' => $contract->contractData->passport_date->format('d.m.Y'),
            'client_passport_place' => $contract->contractData->passport_place,
            'client_passport_code' => $contract->contractData->passport_code,

            'organization_title' => $contract->subscription->service->requisites->organization_title,
            'organization_inn' => $contract->subscription->service->requisites->organization_inn,
            'organization_kpp' => $contract->subscription->service->requisites->organization_kpp,
            'bank_account' => $contract->subscription->service->requisites->bank_account,
            'bank_title' => $contract->subscription->service->requisites->bank_title,
            'bank_bik' => $contract->subscription->service->requisites->bank_bik,
            'bank_ks' => $contract->subscription->service->requisites->bank_ks,
        ]);
        $html = $view->render();

        return Pdf::generate($html, 'a4', 'portrait');
    }

    protected static function formatDate(?Carbon $date, string $suffix = null): string
    {
        if ($date === null) {
            return '*дата не назначена*';
        }

        return $date->translatedFormat('«j»') . ' ' . $date->getTranslatedMonthName('Do MMMM') . ' ' . $date->year . ($suffix ? ' ' . $suffix : '');
    }
}
