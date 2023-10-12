<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Mail\SubscriptionContractFillLinkMail;
use App\Models\Clients\Client;
use App\Models\Clients\ClientWard;
use App\Models\Dictionaries\ClientCommentActionType;
use App\Models\Dictionaries\ClientWardStatus;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Services\Service;
use App\Models\Subscriptions\Subscription;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class ClientsAddSubscriptionController extends ApiEditController
{
    protected array $rules = [
        'region_id' => 'nullable',
        'object_id' => 'nullable',
        'service_id' => 'required',
        'ward_id' => 'nullable',
        'ward_lastname' => 'required_if:ward_id,null',
        'ward_firstname' => 'required_if:ward_id,null',
        'ward_patronymic' => 'required_if:ward_id,null',
        'ward_birth_date' => 'required_if:ward_id,null',
        'contract_comment' => 'nullable',
    ];

    protected array $titles = [
        'region_id' => 'Район',
        'object_id' => 'Объект',
        'service_id' => 'Услуга',
        'ward_id' => 'Занимающийся',
        'ward_lastname' => 'Фамилия занимающегося',
        'ward_firstname' => 'Имя занимающегося',
        'ward_patronymic' => 'Отчество занимающегося',
        'ward_birth_date' => 'Дата рождения занимающегося',
        'contract_comment' => 'Комментарий клиенту',
    ];

    /**
     * Get edit data for client.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var Client|null $client */
        $client = Client::query()
            ->with(['user.profile', 'wards.user.profile'])
            ->where('id', $request->input('client_id'))
            ->tap(new ForOrganization($current->organizationId(), true))
            ->first();

        if ($client === null) {
            return APIResponse::notFound('Клиент не найден');
        }

        /** @var ClientWard|null $ward */
        $ward = $client->wards->count() === 1 ? $client->wards[0] : null;

        $values = [
            'region_id' => null,
            'object_id' => null,
            'service_id' => null,
            'ward_id' => $ward ? $ward->id : null,
            'ward_lastname' => null,
            'ward_firstname' => null,
            'ward_patronymic' => null,
            'ward_birth_date' => null,
            'contract_comment' => null,
        ];

        // send response
        return APIResponse::form(
            $values,
            $this->rules,
            $this->titles,
            [
                'services' => Service::query()
                    ->leftJoin('training_bases', 'training_bases.id', '=', 'services.training_base_id')
                    ->leftJoin('training_base_info', 'training_bases.id', '=', 'training_base_info.base_id')
                    ->where(['services.organization_id' => $current->organizationId(), 'services.status_id' => ServiceStatus::enabled])
                    ->select([
                        'services.id',
                        'services.title',
                        'services.training_base_id',
                        DB::raw('IFNULL(training_bases.short_title, training_bases.title) as base'),
                        'training_base_info.address',
                        'training_bases.region_id',
                    ])
                    ->orderBy('services.title')
                    ->get(),
                'wards' => $client->wards->map(function (ClientWard $ward) {
                    return [
                        'id' => $ward->id,
                        'name' => $ward->user->profile->fullName,
                        'ward_lastname' => $ward->user->profile->lastname,
                        'ward_firstname' => $ward->user->profile->firstname,
                        'ward_patronymic' => $ward->user->profile->patronymic,
                        'ward_birth_date' => $ward->user->profile->birthdate->format('Y-m-d'),
                    ];
                }),
            ]
        );
    }

    /**
     * Update client data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var Client|null $client */
        $client = Client::query()
            ->with(['user.profile', 'wards.user.profile'])
            ->where('id', $request->input('client_id'))
            ->tap(new ForOrganization($current->organizationId(), true))
            ->first();

        if ($client === null) {
            return APIResponse::notFound('Клиент не найден');
        }

        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        try {
            DB::transaction(static function () use ($client, $data, $current) {
                // create client ward if specified
                if ($data['ward_id'] === null) {
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

                    // attach ward to client
                    $client->wards()->attach($ward->id);
                } else {
                    $ward = $client->wards()->where('id', $data['ward_id'])->first();
                }

                if($ward===null){
                    throw new InvalidArgumentException('Занимающийся не найден');
                }

                // create subscription
                $subscription = new Subscription();
                $subscription->setStatus(SubscriptionStatus::new, false);
                $subscription->organization_id = $current->organizationId();
                $subscription->client_id = $client->id;
                $subscription->client_ward_id = $ward->id;
                $subscription->service_id = $data['service_id'];
                $subscription->save();

                // attach comment to client
                $client->addComment($data['contract_comment'], ClientCommentActionType::add_subscription);

                // send a link to client
                try {
                    Mail::send(new SubscriptionContractFillLinkMail($subscription, $data['contract_comment']));
                } catch (Exception $exception) {
                    Log::channel('outgoing_mail_errors')->error($exception->getMessage());
                    throw $exception;
                }
            });
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Подписка добавлена. Ссылка на заполнение договора отправлена клиенту.');
    }
}
