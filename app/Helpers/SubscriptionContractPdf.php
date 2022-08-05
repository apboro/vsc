<?php

namespace App\Helpers;

use App\Models\Subscriptions\SubscriptionContract;
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
        $lines = [];

        $data = $contract->contractData;

        $lines[] = 'Клиент:';
        $lines[] = 'Фамилия: ' . $data->lastname;
        $lines[] = 'Имя: ' . $data->firstname;
        $lines[] = 'Отчество: ' . $data->patronymic;
        $lines[] = 'Телефон: ' . $data->phone;
        $lines[] = 'Email: ' . $data->email;
        $lines[] = 'Серия паспорта: ' . $data->passport_serial;
        $lines[] = 'Номер паспорта: ' . $data->passport_number;
        $lines[] = 'Кем выдан: ' . $data->passport_place;
        $lines[] = 'Дата выдачи: ' . $data->passport_date->format('d.m.Y');
        $lines[] = 'Код подразделения: ' . $data->passport_code;
        $lines[] = 'Адрес регистрации: ' . $data->registration_address;

        $lines[] = 'Занимающийся:';
        $lines[] = 'Фамилия: ' . $data->ward_lastname;
        $lines[] = 'Имя: ' . $data->ward_firstname;
        $lines[] = 'Отчество: ' . $data->ward_patronymic;
        $lines[] = 'Дата рождения: ' . $data->ward_birth_date->format('d.m.Y');
        $lines[] = 'Свидетельство о рождении: ' . $data->ward_document;
        $lines[] = 'Дата выдачи: ' . $data->ward_document_date->format('d.m.Y');

        $view = View::make('pdf/subscription_contract', ['lines' => $lines]);
        $html = $view->render();

        return Pdf::generate($html, 'a4', 'portrait');
    }
}
