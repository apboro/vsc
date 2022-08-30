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

        $schedule = implode(
            "\n",
            array_filter([
                $service->schedule->mon ? ('пн' . ($service->schedule->mon_start_time ? $service->schedule->mon_start_time->format(' - H:i') : null)) : null,
                $service->schedule->tue ? ('вт' . ($service->schedule->tue_start_time ? $service->schedule->tue_start_time->format(' - H:i') : null)) : null,
                $service->schedule->wed ? ('ср' . ($service->schedule->wed_start_time ? $service->schedule->wed_start_time->format(' - H:i') : null)) : null,
                $service->schedule->thu ? ('чт' . ($service->schedule->thu_start_time ? $service->schedule->thu_start_time->format(' - H:i') : null)) : null,
                $service->schedule->fri ? ('пт' . ($service->schedule->fri_start_time ? $service->schedule->fri_start_time->format(' - H:i') : null)) : null,
                $service->schedule->sat ? ('сб' . ($service->schedule->sat_start_time ? $service->schedule->sat_start_time->format(' - H:i') : null)) : null,
                $service->schedule->sun ? ('вс' . ($service->schedule->sun_start_time ? $service->schedule->sun_start_time->format(' - H:i') : null)) : null,
            ], function ($day) {
                return $day !== null;
            })
        );

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
            'schedule' => empty($schedule) ? null : $schedule,
        ]);
    }

}
