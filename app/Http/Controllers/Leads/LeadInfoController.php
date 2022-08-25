<?php

namespace App\Http\Controllers\Leads;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Services\Service;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadInfoController extends ApiEditController
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
            ->tap(new ForOrganization($organizationId))
            ->first();

        if ($service === null) {
            return APIResponse::error('Услуга не найдена');
        }

        $scheduleDays = array_filter([
            $service->schedule->mon ? 'пн' : null,
            $service->schedule->tue ? 'вт' : null,
            $service->schedule->wed ? 'ср' : null,
            $service->schedule->thu ? 'чт' : null,
            $service->schedule->fri ? 'пт' : null,
            $service->schedule->sat ? 'вб' : null,
            $service->schedule->sun ? 'вс' : null,
        ], function ($day) {
            return $day !== null;
        });

        // response success
        return APIResponse::response([
            'training_base_title' => $service->trainingBase->title,
            'training_base_address' => $service->trainingBase->info->address,
            'service_sport_kind' => $service->sportKind->name,
            'service_monthly_price' => $service->monthly_price,
            'service_trainings_per_week' => $service->trainings_per_week,
            'service_trainings_per_month' => $service->trainings_per_month,
            'service_training_duration' => $service->training_duration,
            'service_start_at' => $service->start_at->format('d.m.Y'),
            'service_end_at' => $service->end_at->format('d.m.Y'),
            'schedule_days' => empty($scheduleDays) ? null : implode(', ', $scheduleDays),
            'schedule_start_time' => $service->schedule->start_time ? $service->schedule->start_time->format('H:i') : null,
        ]);
    }

}
