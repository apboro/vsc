<?php

namespace App\Http\Controllers\Leads;

use App\Helpers\SubscriptionContractPdf;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Clients\ClientWard;
use App\Models\Dictionaries\ClientWardStatus;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Models\Subscriptions\SubscriptionContractData;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadContractController extends ApiEditController
{
    /**
     * Save subscription contract data.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function contract(Request $request): JsonResponse
    {
        $organizationId = LeadSession::getOrganizationId($request);

        if ($organizationId === null) {
            return APIResponse::error('Ошибка сессии.', ['oid is null']);
        }

        $subscriptionId = $request->input('subscription_id');

        $subscription = Subscription::query()
            ->with([
                'service',
                'client.user.profile',
                'clientWard.user.profile',
            ])
            ->tap(new ForOrganization($organizationId))
            ->where('id', $subscriptionId)
            ->first();

        if ($subscription === null) {
            return APIResponse::error('Ошибка параметров.');
        }
        if (!$subscription->hasStatus(SubscriptionStatus::new) && !$subscription->hasStatus(SubscriptionStatus::fill)) {
            return APIResponse::error('Форма уже заполнена.');
        }

        /** @var Subscription $subscription */

        $data = $this->getData($request);

        // validate
        $rules = [
            'lastname' => 'required',
            'firstname' => 'required',
            'patronymic' => 'required',
            'phone' => 'required',
            'email' => 'required|email|bail',
            'birth_date' => 'required',
            'passport_serial' => 'required',
            'passport_number' => 'required',
            'passport_place' => 'required',
            'passport_date' => 'required',
            'passport_code' => 'required',
            'registration_address' => 'required',
            'ward_lastname' => 'required',
            'ward_firstname' => 'required',
            'ward_patronymic' => 'required',
            'ward_birth_date' => 'required',
            'ward_document' => 'required',
            'ward_document_date' => 'required',
            'discount' => 'nullable',
        ];
        $titles = [
            'lastname' => 'Фамилия',
            'firstname' => 'Имя',
            'patronymic' => 'Отчество',
            'phone' => 'Телефон',
            'email' => 'Email',
            'birth_date' => 'Дата рождения',
            'passport_serial' => 'Серия паспорта',
            'passport_number' => 'Номер паспорта',
            'passport_place' => 'Кем выдан',
            'passport_date' => 'Дата выдачи',
            'passport_code' => 'Код подразделения',
            'registration_address' => 'Адрес регистрации',
            'ward_lastname' => 'Фамилия',
            'ward_firstname' => 'Имя',
            'ward_patronymic' => 'Отчество',
            'ward_birth_date' => 'Дата рождения',
            'ward_document' => 'Свидетельство о рождении',
            'ward_document_date' => 'Дата выдачи',
            'discount' => 'Основание для льготы',
        ];
        if ($errors = $this->validate($data, $rules, $titles)) {
            return APIResponse::validationError($errors);
        }

        try {
            DB::transaction(static function () use ($subscription, $data) {
                // add contract
                $contract = new SubscriptionContract();
                $contract->subscription_id = $subscription->id;
                $contract->discount_id = $data['discount'];
                $contract->setStatus(SubscriptionContractStatus::draft, false);
                $contract->save();

                // fill contract data
                $contract->contractData->lastname = trim($data['lastname']);
                $contract->contractData->firstname = trim($data['firstname']);
                $contract->contractData->patronymic = trim($data['patronymic']);
                $contract->contractData->phone = $data['phone'];
                $contract->contractData->email = trim($data['email']);
                $contract->contractData->birth_date = Carbon::parse($data['birth_date']);
                $contract->contractData->passport_serial = trim($data['passport_serial']);
                $contract->contractData->passport_number = trim($data['passport_number']);
                $contract->contractData->passport_place = trim($data['passport_place']);
                $contract->contractData->passport_date = Carbon::parse($data['passport_date']);
                $contract->contractData->passport_code = trim($data['passport_code']);
                $contract->contractData->registration_address = trim($data['registration_address']);
                $contract->contractData->ward_lastname = trim($data['ward_lastname']);
                $contract->contractData->ward_firstname = trim($data['ward_firstname']);
                $contract->contractData->ward_patronymic = trim($data['ward_patronymic']);
                $contract->contractData->ward_birth_date = Carbon::parse($data['ward_birth_date']);
                $contract->contractData->ward_document = trim($data['ward_document']);
                $contract->contractData->ward_document_date = Carbon::parse($data['ward_document_date']);
                $contract->contractData->save();

                $client = $subscription->client;
                $client->user->profile->lastname = trim($data['lastname']);
                $client->user->profile->firstname = trim($data['firstname']);
                $client->user->profile->patronymic = trim($data['patronymic']);
                $client->user->profile->phone = $data['phone'];
                $client->user->profile->email = trim($data['email']);
                $client->user->profile->birthdate = Carbon::parse($data['birth_date']);
                $client->user->profile->save();

                $ward = $subscription->clientWard;
                $ward->user->profile->lastname = trim($data['ward_lastname']);
                $ward->user->profile->firstname = trim($data['ward_firstname']);
                $ward->user->profile->patronymic = trim($data['ward_patronymic']);
                $ward->user->profile->birthdate = Carbon::parse($data['ward_birth_date']);
                $ward->user->profile->save();

                // update subscription status
                $subscription->setStatus(SubscriptionStatus::filled, false);
                $subscription->save();
            });
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return APIResponse::error('Возникла ошибка.');
        }

        // response success
        return APIResponse::success('Ваша заявка отправлена.');
    }

    public function preview(?string $subscriptionKey)
    {
        if (empty($subscriptionKey)) {
            abort(404);
        }

        try {
            $subscriptionId = Crypt::decrypt($subscriptionKey);
        } catch (Exception $exception) {
            abort(404);
        }

        /** @var Subscription|null $subscription */
        $subscription = Subscription::query()->where('id', $subscriptionId)->with(['client', 'clientWard', 'service'])->first();

        // make dummy contract
        $contract = new SubscriptionContract();
        $contract->contractData = new SubscriptionContractData();

        $contract->contractData->lastname = '____________';
        $contract->contractData->firstname = '____________';
        $contract->contractData->patronymic = '____________';
        $contract->contractData->phone = '____________';
        $contract->contractData->email = '____________';
        $contract->contractData->passport_serial = '____________';
        $contract->contractData->passport_number = '____________';
        $contract->contractData->passport_place = '____________';
        $contract->contractData->passport_date = null;
        $contract->contractData->passport_code = '____________';
        $contract->contractData->registration_address = '____________';
        $contract->contractData->ward_lastname = '____________';
        $contract->contractData->ward_firstname = '____________';
        $contract->contractData->ward_patronymic = '____________';
        $contract->contractData->ward_birth_date = null;
        $contract->contractData->ward_document = '____________';
        $contract->contractData->ward_document_date = null;

        $contract->subscription_id = $subscription->id;

        $pdf = SubscriptionContractPdf::generate($contract);

        return response($pdf, 200, [
            'Cache-Control' => 'public',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"договор.pdf\"",
        ]);
    }
}
