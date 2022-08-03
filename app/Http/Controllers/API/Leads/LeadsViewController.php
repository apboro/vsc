<?php

namespace App\Http\Controllers\API\Leads;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Leads\Lead;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadsViewController extends ApiController
{
    public function view(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var Lead $lead */
        if ($id === null ||
            null === ($lead = Lead::query()
                ->with(['status', 'service', 'service.trainingBase', 'service.sportKind', 'client.user.profile'])
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Лид не найден');
        }

        $values = [
            'title' => "$lead->lastname $lead->firstname $lead->patronymic",
            'lastname' => $lead->lastname,
            'firstname' => $lead->firstname,
            'patronymic' => $lead->patronymic,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'status' => $lead->status->name,
            'created_date' => $lead->created_at->format('d.m.Y'),
            'created_time' => $lead->created_at->format('H:i'),
            'service' => $lead->service->title ?? null,
            'service_id' => $lead->service_id,
            'training_base' => $lead->service ? $lead->service->trainingBase->short_title : null,
            'sport_kind' => $lead->service ? $lead->service->sportKind->name : null,
            'client' => $lead->client ? $lead->client->user->profile->compactName : null,
            'client_id' => $lead->client_id,
            'is_registrable' => $lead->client_id === null && $current->can('leads.register'),
        ];

        // send response
        return APIResponse::response($values);
    }
}
