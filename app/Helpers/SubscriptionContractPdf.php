<?php

namespace App\Helpers;

use App\Models\PositionService;
use App\Models\Subscriptions\SubscriptionContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class SubscriptionContractPdf
{
    /**
     * Generate ticket PDF.
     *
     * @param SubscriptionContract $contract
     * @param bool $signed
     *
     * @return  string|null
     */
    public static function generate(SubscriptionContract $contract, bool $signed = false): ?string
    {
        $contract->loadMissing([
            'contractData',
            'subscription',
            'discount',
            'subscription.service',
            'subscription.service.contract.pattern',
            'subscription.service.sportKind',
            'subscription.service.trainingBase.info',
        ]);

        $view = 'pdf/subscription_contract';
        if (isset($contract->subscription->service->contract) && isset($contract->subscription->service->contract->pattern)) {
            $view = $contract->subscription->service->contract->pattern->pattern;
        }
        $monthlyPrice = $contract->contractData->monthly_price ?? $contract->subscription->service->monthly_price;
        $trainingReturnPrice = $contract->contractData->training_return_price ?? $contract->subscription->service->training_return_price;

        $requisites = $contract->subscription->service->requisites;

        $values = [
            'signed' => $signed,

            'contract_number' => $contract->number ?? '*не назначен*',
            'contract_date' => self::formatDate($contract->start_at, 'г.'),

            'client_name' => $contract->contractData->lastname . ' ' . $contract->contractData->firstname . ' ' . $contract->contractData->patronymic,
            'client_compact_name' => $contract->contractData->lastname . ' ' .
                mb_substr($contract->contractData->firstname, 0, 1) . '. ' .
                mb_substr($contract->contractData->patronymic, 0, 1) . '.',
            'client_phone' => $contract->contractData->phone,
            'client_email' => $contract->contractData->email,
            'client_address' => $contract->contractData->registration_address,
            'client_birth_date' => $contract->contractData->birth_date ? $contract->contractData->birth_date->format('d.m.Y') : '____',
            'client_passport_serial' => $contract->contractData->passport_serial,
            'client_passport_number' => $contract->contractData->passport_number,
            'client_passport_date' => $contract->contractData->passport_date ? $contract->contractData->passport_date->format('d.m.Y') : '____',
            'client_passport_place' => $contract->contractData->passport_place,
            'client_passport_code' => $contract->contractData->passport_code,

            'ward_name' => $contract->contractData->ward_lastname . ' ' . $contract->contractData->ward_firstname . ' ' . $contract->contractData->ward_patronymic,
            'ward_birth_date' => $contract->contractData->ward_birth_date ? $contract->contractData->ward_birth_date->format('d.m.Y') : '____',
            'service_name' => $contract->contractData->service_name,

            'ward_document' => $contract->contractData->ward_document ?? '____',
            'ward_document_date' => $contract->contractData->ward_document_date ? $contract->contractData->ward_document_date->format('d.m.Y') : '____',

            'service_start_date' => self::formatDate($contract->contractData->service_start_date ?? $contract->subscription->service->start_at, 'г.'),
            'service_end_date' => self::formatDate($contract->contractData->service_end_date ?? $contract->subscription->service->end_at, 'г.'),

            'price' => $contract->contractData->price,
            'daily_price' => $contract->contractData->daily_price,
            'advance_payment' => $contract->contractData->advance_payment,
            'refund_amount' => $contract->contractData->refund_amount,
            'date_advance_payment' => self::formatDate($contract->contractData->date_advance_payment, 'г.'),
            'date_deposit_funds' => self::formatDate($contract->contractData->date_deposit_funds, 'г.'),
            'training_base_name' => $contract->contractData->training_base_name,
            'trainings_per_week' => $contract->contractData->trainings_per_week ?? $contract->subscription->service->trainings_per_week,
            'trainings_per_month' => $contract->contractData->trainings_per_month ?? $contract->subscription->service->trainings_per_month,
            'training_duration' => $contract->contractData->training_duration ?? $contract->subscription->service->training_duration,

            'schedule' => implode(
                ", ",
                array_filter([
                    $contract->subscription->service->schedule->mon ? ('пн' . ($contract->subscription->service->schedule->mon_start_time ? ' с ' . $contract->subscription->service->schedule->mon_start_time->format('H:i') : null)) : null,
                    $contract->subscription->service->schedule->tue ? ('вт' . ($contract->subscription->service->schedule->tue_start_time ? ' с ' . $contract->subscription->service->schedule->tue_start_time->format('H:i') : null)) : null,
                    $contract->subscription->service->schedule->wed ? ('ср' . ($contract->subscription->service->schedule->wed_start_time ? ' с ' . $contract->subscription->service->schedule->wed_start_time->format('H:i') : null)) : null,
                    $contract->subscription->service->schedule->thu ? ('чт' . ($contract->subscription->service->schedule->thu_start_time ? ' с ' . $contract->subscription->service->schedule->thu_start_time->format('H:i') : null)) : null,
                    $contract->subscription->service->schedule->fri ? ('пт' . ($contract->subscription->service->schedule->fri_start_time ? ' с ' . $contract->subscription->service->schedule->fri_start_time->format('H:i') : null)) : null,
                    $contract->subscription->service->schedule->sat ? ('сб' . ($contract->subscription->service->schedule->sat_start_time ? ' с ' . $contract->subscription->service->schedule->sat_start_time->format('H:i') : null)) : null,
                    $contract->subscription->service->schedule->sun ? ('вс' . ($contract->subscription->service->schedule->sun_start_time ? ' с ' . $contract->subscription->service->schedule->sun_start_time->format('H:i') : null)) : null,
                ], function ($day) {
                    return $day !== null;
                })
            ),

            'sport_kind' => $contract->contractData->sport_kind ?? implode(', ', $contract->subscription->service->sportKinds->pluck('name')->toArray()),
            'training_base_address' => $contract->contractData->training_base_address ?? $contract->subscription->service->trainingBase->info->address,

            'monthly_price' => $monthlyPrice . ' руб.',
            'monthly_price_string' => PriceConverter::toString($monthlyPrice),
            'training_return_price' => $trainingReturnPrice . ' руб.',
            'training_return_price_string' => PriceConverter::toString($trainingReturnPrice),

            'discount_1_monthly_price' => ($monthlyPrice * 0.9) . ' руб.',
            'discount_1_monthly_price_string' => PriceConverter::toString(($monthlyPrice * 0.9)),
            'discount_2_monthly_price' => ($monthlyPrice * 0.9) . ' руб.',
            'discount_2_monthly_price_string' => PriceConverter::toString(($monthlyPrice * 0.9)),
            'discount_3_monthly_price' => ($monthlyPrice * 0.9) . ' руб.',
            'discount_3_monthly_price_string' => PriceConverter::toString(($monthlyPrice * 0.9)),

            //'training_base_phone' => $contract->subscription->service->trainingBase->info->phone,
            'training_base_phone' => $contract->subscription->service->phonesList(),
            //'training_base_email' => $contract->subscription->service->trainingBase->info->email,
            'training_base_email' => $contract->subscription->service->email,
            'training_base_homepage' => $contract->subscription->service->trainingBase->info->homepage,

            'header_of_contract'=>!empty($requisites->header_of_contract) ? $requisites->header_of_contract : null,
            'organization_title' => $contract->contractData->organization_title ?? $contract->subscription->service->requisites->organization_title,
            'organization_inn' => $contract->contractData->organization_inn ?? $contract->subscription->service->requisites->organization_inn,
            'organization_kpp' => $contract->contractData->organization_kpp ?? $contract->subscription->service->requisites->organization_kpp,
            'bank_account' => $contract->contractData->bank_account ?? $contract->subscription->service->requisites->bank_account,
            'bank_title' => $contract->contractData->bank_title ?? $contract->subscription->service->requisites->bank_title,
            'bank_bik' => $contract->contractData->bank_bik ?? $contract->subscription->service->requisites->bank_bik,
            'bank_ks' => $contract->contractData->bank_ks ?? $contract->subscription->service->requisites->bank_ks,

            'organization_phone' => !empty($requisites->phone) ? $requisites->phone : null,
            'organization_email' => !empty($requisites->email) ? $requisites->email : null,
            'organization_homepage' => !empty($requisites->web_site) ? $requisites->web_site : null,
            'organization_ogrn' => !empty($requisites->organization_ogrn) ? $requisites->organization_ogrn : null,
            'legal_address' => !empty($requisites->legal_address) ? $requisites->legal_address : null,
            'sign'=>!empty($requisites->sign) ? $requisites->sign : null,
        ];

        if ($contract->is_group) {
            $values = array_merge($values, [
                'responsible_manager' => $contract->subscription->service->positions->map(function (PositionService $ps) {
                    return $ps->position->user->profile->fullName ?? '';
                })->join(', '),
                'service_email' => $contract->subscription->service->email,
                'service_phone' => $contract->subscription->service->phonesList(),

                'service_daily_price' => $contract->contractData->price,
                'group_price' => $contract->contractData->group_price ?:
                    ($contract->groupData->ward_count * $contract->subscription->service->price ?? 0),
                'additional_price' => $contract->contractData->additional_price,
                'total_price' => $contract->contractData->total_price,
                'service_description' => $contract->subscription->service->description,
                'days_count' => $contract->contractData->days_count ?? $contract->subscription->service->days_count,

                'is_legal' => $contract->is_legal,
                'additional_conditions' => $contract->contractData->additional_conditions,

                'girls_1_count' => $contract->groupData->girls_1_count,
                'boys_1_count' => $contract->groupData->boys_1_count,
                'girls_2_count' => $contract->groupData->girls_2_count,
                'boys_2_count' => $contract->groupData->boys_2_count,
                'girls_3_count' => $contract->groupData->girls_3_count,
                'boys_3_count' => $contract->groupData->boys_3_count,
                'ward_count' => $contract->groupData->ward_count,
                'trainer_count' => $contract->groupData->trainer_count,
                'attendant_count' => $contract->groupData->attendant_count,

                'org_name' => $contract->organizationData->organization_name,
                'org_in_face' => $contract->organizationData->in_face,
                'org_inn' => $contract->organizationData->inn,
                'org_kpp' => $contract->organizationData->kpp,
                'org_checking_account' => $contract->organizationData->checking_account,
                'org_bic' => $contract->organizationData->bic,
                'org_corr_account' => $contract->organizationData->corr_account,
                'org_email' => $contract->organizationData->org_email,
                'org_phone' => $contract->organizationData->org_phone,
                'org_ogrn' => $contract->organizationData->org_ogrn ?? null, // todo
            ]);
        }

        $view = View::make($view, $values);
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
