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
                    'groupData',
                ])
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Лид не найден');
        }

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

            'is_group' => $lead->is_group,

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

            'can_register' => $lead->canRegister($current->position()),
            'can_delete' => $lead->canDelete($current->position()),
        ];

        //  Append group data
        if ($lead->is_group) {
            $values['organization_name'] = $lead->groupData->organization_name;
            $values['is_contract_legal'] = $lead->groupData->is_contract_legal;
            $values['girls_1_count'] = $lead->groupData->girls_1_count ?? 0;
            $values['boys_1_count'] = $lead->groupData->boys_1_count ?? 0;
            $values['girls_2_count'] = $lead->groupData->girls_2_count ?? 0;
            $values['boys_2_count'] = $lead->groupData->boys_2_count ?? 0;
            $values['girls_3_count'] = $lead->groupData->girls_3_count ?? 0;
            $values['boys_3_count'] = $lead->groupData->boys_3_count ?? 0;
            $values['ward_count'] = $lead->groupData->ward_count ?? 0;
            $values['trainer_count'] = $lead->groupData->trainer_count;
            $values['attendant_count'] = $lead->groupData->attendant_count;
        }

        if($values['can_register']) {
            $services = Service::query()
                ->leftJoin('training_bases', 'training_bases.id', '=', 'services.training_base_id')
                ->leftJoin('training_base_info', 'training_bases.id', '=', 'training_base_info.base_id')
                ->where(['services.organization_id' => $current->organizationId(), 'services.status_id' => ServiceStatus::enabled])
                ->select([
                    'services.id',
                    'services.title',
                    'services.description',
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
