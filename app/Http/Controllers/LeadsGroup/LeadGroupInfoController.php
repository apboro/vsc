<?php

namespace App\Http\Controllers\LeadsGroup;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Dictionaries\ServiceTypes;
use App\Models\Services\Service;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadGroupInfoController extends ApiEditController
{
    /**
     * Add new lead.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function info(Request $request): JsonResponse
    {
        $organizationId = LeadSession::getOrganizationId($request);

        if ($organizationId === null) {
            return APIResponse::error('Ошибка сессии.', ['oid is null']);
        }

        $serviceId = $request->input('service_id');

        /** @var Service|null $service */
        $service = Service::query()
            ->with(['schedule', 'trainingBase.info', 'sportKind'])
            ->where('id', $serviceId)
            ->whereHas('typeProgram', function (Builder $q) {
                $q->where('service_type_id', ServiceTypes::group);
            })
            ->tap(new ForOrganization($organizationId))
            ->first();

        if ($service === null) {
            return APIResponse::error('Услуга не найдена');
        }

        // response success
        return APIResponse::response([
            'training_base_title' => $service->trainingBase->title,
            'training_base_address' => $service->trainingBase->info->address,
            'service_sport_kind' => $service->sportKind->name,
            'advance_payment' => $service->advance_payment,
            'price' => $service->price,
            'date_deposit_funds' => $service->date_deposit_funds ? $service->date_deposit_funds->format('d.m.Y') : null,
            'service_start_at' => $service->start_at->format('d.m.Y'),
            'date_advance_payment' => $service->date_advance_payment ? $service->date_advance_payment->format('d.m.Y') : null,
            'service_end_at' => $service->end_at->format('d.m.Y'),
            'description' => $service->description,
        ]);
    }

}
