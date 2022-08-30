<?php

namespace App\Http\Controllers\API\Leads;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Leads\Lead;
use App\Models\Services\Service;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadsViewController extends ApiController
{
    public function view(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var Lead $lead */
        if ($id === null ||
            null === ($lead = Lead::query()
                ->with([
                    'status',
                    'service', 'service.trainingBase', 'service.trainingBase.info', 'service.sportKind',
                    'region',
                    'subscription.client.user.profile',
                    'subscription.clientWard.user.profile',
                ])
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Лид не найден');
        }

        $isRegisterable = $lead->subscription_id === null && $current->can('leads.register');

        $values = [
            'title' => "$lead->lastname $lead->firstname $lead->patronymic",
            'status' => $lead->status->name,
            'created_date' => $lead->created_at->format('d.m.Y'),
            'created_time' => $lead->created_at->format('H:i'),

            'lastname' => $lead->lastname,
            'firstname' => $lead->firstname,
            'patronymic' => $lead->patronymic,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'client' => $lead->subscription && $lead->subscription->client ? $lead->subscription->client->user->profile->compactName : null,
            'client_id' => $lead->subscription ? $lead->subscription->client_id : null,

            'ward_lastname' => $lead->ward_lastname,
            'ward_firstname' => $lead->ward_firstname,
            'ward_patronymic' => $lead->ward_patronymic,
            'ward_birth_date_info' => $lead->ward_birth_date ? $lead->ward_birth_date->format('d.m.Y') : null,
            'ward_birth_date' => $lead->ward_birth_date ? $lead->ward_birth_date->format('Y-m-d') : null,

            'ward_age' => $lead->ward_birth_date ? $lead->ward_birth_date->age . ' ' . trans_choice('год|года|лет', $lead->ward_birth_date->age) : null,

            'ward_inv' => $lead->ward_inv,
            'ward_hro' => $lead->ward_hro,
            'ward_uch' => $lead->ward_uch,
            'ward_spe' => $lead->ward_spe,

            'region' => $lead->region->name ?? null,
            'region_id' => $lead->region_id,
            'service' => $lead->service->title ?? null,
            'service_id' => $lead->service_id,
            'need_help' => $lead->need_help,
            'training_base' => $lead->service ? ($lead->service->trainingBase->short_title ?? $lead->service->trainingBase->title) : null,
            'training_base_address' => $lead->service ? $lead->service->trainingBase->info->address : null,
            'sport_kind' => $lead->service ? $lead->service->sportKind->name : null,

            'client_comments' => $lead->client_comments,
            'comments' => $lead->comments,

            'is_registrable' => $isRegisterable,
        ];

        if($isRegisterable) {
            $services = Service::query()
                ->leftJoin('training_bases', 'training_bases.id', '=', 'services.training_base_id')
                ->leftJoin('training_base_info', 'training_bases.id', '=', 'training_base_info.base_id')
                ->where(['services.organization_id' => $current->organizationId(), 'services.status_id' => ServiceStatus::enabled])
                ->select([
                    'services.id',
                    'services.title',
                    DB::raw('IFNULL(training_bases.short_title, training_bases.title) as base'),
                    'training_base_info.address',
                    'training_bases.region_id',
                ])
                ->orderBy('services.title')
                ->get();
        }

        // send response
        return APIResponse::response($values, [
            'services' => $services ?? [],
        ]);
    }
}
