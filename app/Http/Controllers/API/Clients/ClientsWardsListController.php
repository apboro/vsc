<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Clients\Client;
use App\Models\Clients\ClientWard;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientsWardsListController extends ApiController
{
    protected string $rememberKey = CookieKeys::clients_wards_list;

    /**
     * Get clients wards list.
     *
     * @param APIListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $this->rememberKey = CookieKeys::getKey($this->rememberKey, $current->organizationId());

        /** @var Client|null $client */
        $client = Client::query()
            ->where('id', $request->input('client_id'))
            ->tap(new ForOrganization($current->organizationId()))
            ->first();

        if ($client === null) {
            return APIResponse::error('Клиент не найден');
        }

        $query = $client->wards()->with(['user.profile']);

        // current page automatically resolved from request via `page` parameter
        $wards = $query->paginate($request->perPage(10, $this->rememberKey));

        /** @var LengthAwarePaginator $wards */
        $wards->transform(function (ClientWard $ward) {
            return [
                'id' => $ward->id,
                'name' => $ward->user->profile->fullName,
                'birth_date' => $ward->user->profile->birthdate ? $ward->user->profile->birthdate->format('d.m.Y') : null,
            ];
        });

        return APIResponse::list(
            $wards,
            ['ID', 'ФИО', 'Дата рождения'],
            [],
            [],
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
