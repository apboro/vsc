<?php

namespace App\Http\Controllers\API\Leads;

use App\Current;
use App\Helpers\DuplicatesFinder;
use App\Helpers\PriceConverter;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Requests\APIListRequest;
use App\Mail\SubscriptionContractFillLinkMail;
use App\Models\Clients\Client;
use App\Models\Clients\ClientWard;
use App\Models\Dictionaries\ClientCommentActionType;
use App\Models\Dictionaries\ClientStatus;
use App\Models\Dictionaries\ClientWardStatus;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Leads\Lead;
use App\Models\Subscriptions\Subscription;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadsRegisterController extends ApiEditController
{
    protected array $rules = [
        'lastname' => 'required',
        'firstname' => 'required',
        'patronymic' => 'required',
        'phone' => 'required',
        'email' => 'required|email|bail',
        'service_id' => 'required',
        'contract_comment' => 'nullable',
    ];
    protected array $titles = [
        'lastname' => 'Фамилия',
        'firstname' => 'Имя',
        'patronymic' => 'Отчество',
        'phone' => 'Телефон',
        'email' => 'Email',
        'service_id' => 'Услуга',
        'contract_comment' => 'Комментарий клиенту',
    ];

    /** @noinspection DuplicatedCode */
    public function register(Request $request): JsonResponse
    {
        $id = $request->input('lead_id');
        $current = Current::get($request);

        /** @var Lead $lead */
        if ($id === null ||
            null === ($lead = Lead::query()
                ->with(['status', 'service', 'service.trainingBase', 'service.sportKind'])
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Лид не найден');
        }

        if ($lead->subscription_id !== null) {
            return APIResponse::error('По этому лиду уже создана подписка.');
        }

        // validate data
        $data = $this->getData($request);

        if ($lead->is_group) {
            $this->rules['is_contract_legal'] = 'required|boolean';
            $this->rules['organization_name'] = 'nullable|string';

            $this->titles['is_contract_legal'] = 'Договор оформляется на юр. лицо';
            $this->titles['organization_name'] = 'Название организации';
        } else {
            $this->rules['ward_lastname'] = 'required';
            $this->rules['ward_firstname'] = 'required';
            $this->rules['ward_patronymic'] = 'required';
            $this->rules['ward_birth_date'] = 'required';

            $this->titles['ward_lastname'] = 'Фамилия занимающегося';
            $this->titles['ward_firstname'] = 'Имя занимающегося';
            $this->titles['ward_patronymic'] = 'Отчество занимающегося';
            $this->titles['ward_birth_date'] = 'Дата рождения занимающегося';
        }

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        try {
            DB::transaction(static function () use ($lead, $data, $current) {
                if ($data['client_id']) {
                    /** @var Client|null $client */
                    $client = Client::query()->where('id', $data['client_id'])->with('user.profile')->first();

                    if (!$client) {
                        return APIResponse::error('Клиент не существует');
                    }

                    if ($data['update_client_lastname']) {
                        $client->user->profile->update(['lastname' => $data['lastname']]);
                    }

                    if ($data['update_client_firstname']) {
                        $client->user->profile->update(['firstname' => $data['firstname']]);
                    }

                    if ($data['update_client_patronymic']) {
                        $client->user->profile->update(['patronymic' => $data['patronymic']]);
                    }

                    if ($data['update_client_phone']) {
                        $client->user->profile->update(['phone' => $data['phone']]);
                    }

                    if ($data['update_client_email']) {
                        $client->user->profile->update(['email' => $data['email']]);
                    }
                } else {
                    // create client
                    $user = new User();
                    $user->save();

                    $user->profile->firstname = $data['firstname'];
                    $user->profile->lastname = $data['lastname'];
                    $user->profile->patronymic = $data['patronymic'];
                    $user->profile->email = $data['email'];
                    $user->profile->phone = $data['phone'];
                    $user->profile->save();

                    $client = new Client();
                    $client->setStatus(ClientStatus::active, false);
                    $client->organization_id = $current->organizationId();
                    $client->user_id = $user->id;
                    $client->save();
                }

                if (!$lead->is_group) {
                    if ($data['ward_id']) {
                        /** @var ClientWard|null $ward */
                        $ward = ClientWard::query()->where('id', $data['ward_id'])->with('user.profile')->first();

                        if (!$ward) {
                            return APIResponse::error('Клиент не существует');
                        }

                        if ($data['update_ward_lastname']) {
                            $client->user->profile->update(['lastname' => $data['ward_lastname']]);
                        }

                        if ($data['update_ward_firstname']) {
                            $client->user->profile->update(['firstname' => $data['ward_firstname']]);
                        }

                        if ($data['update_ward_patronymic']) {
                            $client->user->profile->update(['patronymic' => $data['ward_patronymic']]);
                        }

                        if ($data['update_ward_birth_date']) {
                            $client->user->profile->update(['birthdate' => $data['ward_birth_date']]);
                        }
                    } else {
                        // create client ward
                        $user = new User();
                        $user->save();

                        $user->profile->lastname = $data['ward_lastname'];
                        $user->profile->firstname = $data['ward_firstname'];
                        $user->profile->patronymic = $data['ward_patronymic'];
                        $user->profile->birthdate = Carbon::parse($data['ward_birth_date']);
                        $user->profile->save();

                        $ward = new ClientWard();
                        $ward->user_id = $user->id;
                        $ward->setStatus(ClientWardStatus::active, false);
                        $ward->save();
                    }

                    // attach ward to client
                    if ($client->wards()->where('id', $ward->id)->count() === 0) {
                        $client->wards()->attach($ward->id);
                    }
                } else {
                    $lead->groupData->is_contract_legal = $data['is_contract_legal'];
                    $lead->groupData->organization_name = $data['organization_name'];
                    $lead->groupData->save();
                }

                // create subscription
                $subscription = new Subscription();
                $subscription->setStatus(SubscriptionStatus::new, false);
                $subscription->organization_id = $current->organizationId();
                $subscription->client_id = $client->id;
                $subscription->client_ward_id = $ward->id ?? null;
                $subscription->service_id = $data['service_id'];
                $subscription->save();

                // attach the client to the lead
                $lead->subscription_id = $subscription->id;
                $lead->converted_at = Carbon::now();
                $lead->setStatus(LeadStatus::client_created, false);
                $lead->save();

                // attach comment to client
                $client->addComment($data['contract_comment'], ClientCommentActionType::lead_convert);

                // send a link to client
                if (env('SKIP_LEAD_EMAIL_SENDING', false) === false) {
                    try {
                        Mail::send(new SubscriptionContractFillLinkMail($subscription, $data['contract_comment']));
                    } catch (Exception $exception) {
                        Log::channel('outgoing_mail_errors')->error($exception->getMessage());
                        throw $exception;
                    }
                }
            });
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Клиент зарегистрирован. Ссылка на заполнение договора отправлена клиенту.');
    }

    public function findDuplicates(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $data = $this->getData($request);
        /** @var Lead|null $lead */
        $lead = Lead::query()->find($data['lead_id']);

        $clientDuplicates = DuplicatesFinder::clientDuplicates(
            $current->organizationId(),
            $data['lastname'],
            $data['firstname'],
            $data['patronymic'],
            $data['phone'],
            $data['email']
        );

        if ($clientDuplicates !== null) {
            $clientDuplicates->transform(function (Client $client) {
                return [
                    'id' => $client->id,
                    'name' => $client->user->profile->fullName,
                    'email' => $client->user->profile->email,
                    'phone' => $client->user->profile->phone,
                    'lastname' => $client->user->profile->lastname,
                    'firstname' => $client->user->profile->firstname,
                    'patronymic' => $client->user->profile->patronymic,
                ];
            });
        }

        if ($lead && !$lead->is_group) {
            $wardDuplicates = DuplicatesFinder::wardDuplicates(
                $current->organizationId(),
                $data['ward_lastname'],
                $data['ward_firstname'],
                $data['ward_patronymic'],
                $data['ward_birth_date']
            );

            if ($wardDuplicates !== null) {
                $wardDuplicates->transform(function (ClientWard $clientWard) {
                    return [
                        'id' => $clientWard->id,
                        'name' => $clientWard->user->profile->fullName,
                        'birthdate' => $clientWard->user->profile->birthdate->format('d.m.Y'),
                        'lastname' => $clientWard->user->profile->lastname,
                        'firstname' => $clientWard->user->profile->firstname,
                        'patronymic' => $clientWard->user->profile->patronymic,
                    ];
                });
            }
        }

        return APIResponse::response([
            'clients_info' => $clientDuplicates,
            'wards_info' => $wardDuplicates ?? null,
        ]);
    }
}
