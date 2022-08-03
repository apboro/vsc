<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Clients\Client;
use App\Models\Dictionaries\ClientStatus;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsViewController extends ApiController
{
    public function view(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var Client $client */
        if ($id === null || null === ($client = Client::query()
                ->with(['user.profile', 'status'])
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId(), true))
                ->first())
        ) {
            return APIResponse::notFound('Клиент не найден');
        }

        $values = [
            'title' => $client->user->profile->fullName,
            'lastname' => $client->user->profile->lastname,
            'firstname' => $client->user->profile->firstname,
            'patronymic' => $client->user->profile->patronymic,
            'status' => $client->status->name,
            'active' => $client->hasStatus(ClientStatus::active),
            'email' => $client->user->profile->email,
            'phone' => $client->user->profile->phone,
        ];

        // send response
        return APIResponse::response($values);
    }
}
