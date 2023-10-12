<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Clients\Client;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsEditController extends ApiEditController
{
    protected array $rules = [
        'lastname' => 'required',
        'firstname' => 'required',
        'patronymic' => 'required',
        'phone' => 'required',
        'email' => 'required',
    ];

    protected array $titles = [
        'lastname' => 'Фамилия',
        'firstname' => 'Имя',
        'patronymic' => 'Отчество',
        'phone' => 'Телефон',
        'email' => 'Email',
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
            ->with(['user.profile'])
            ->where('id', $request->input('client_id'))
            ->tap(new ForOrganization($current->organizationId(), true))
            ->first();

        if ($client === null) {
            return APIResponse::notFound('Клиент не найден');
        }

        $values = [
            'lastname' => $client->user->profile->lastname,
            'firstname' => $client->user->profile->firstname,
            'patronymic' => $client->user->profile->patronymic,
            'phone' => $client->user->profile->phone,
            'email' => $client->user->profile->email,
        ];

        // send response
        return APIResponse::form(
            $values,
            $this->rules,
            $this->titles
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
            ->with(['user.profile'])
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

        $profile = $client->user->profile;
        $profile->lastname = $data['lastname'];
        $profile->firstname = $data['firstname'];
        $profile->patronymic = $data['patronymic'];
        $profile->phone = $data['phone'];
        $profile->email = $data['email'];

        DB::transaction(function () use ($profile, $client) {
            $profile->save();

            $client->refresh();

            $client->updateContractsData([
                SubscriptionStatus::new,
                SubscriptionStatus::fill,
                SubscriptionStatus::filled,
            ]);
        });

        return APIResponse::success('Данные клиента обновлены');
    }
}
