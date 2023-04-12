<?php

namespace App\Http\Controllers\API\Services;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\ServiceTypes;
use App\Models\Services\Service;
use App\Models\Services\ServiceProgram;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicesViewController extends ApiController
{
    public function view(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var Service $service */
        if ($id === null ||
            null === ($service = Service::query()
                ->with(['status', 'trainingBase', 'sportKind', 'schedule'])
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Услуга не найдена');
        }

        $values = self::composeServiceData($service);

        // send response
        return APIResponse::response($values);
    }

    public static function composeServiceData(Service $service): array
    {
        $service->loadMissing('status');
        $service->loadMissing('trainingBase');
        $service->loadMissing('sportKind');
        $service->loadMissing('schedule');
        $service->loadMissing('requisites');

        return [
            'status' => $service->status->name,
            'active' => $service->hasStatus(ServiceStatus::enabled),
            'title' => $service->title,
            'training_base' => $service->trainingBase->title,
            'sport_kind' => $service->sportKind->name,
            'schedule' => implode(
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
            ),
            'monthly_price' => $service->monthly_price,
            'training_price' => $service->training_price,
            'trainings_per_week' => $service->trainings_per_week,
            'trainings_per_month' => $service->trainings_per_month,
            'training_return_price' => $service->training_return_price,
            'training_duration' => $service->training_duration,
            'group_limit' => $service->group_limit,
            'start_at' => $service->start_at->format('d.m.Y'),
            'end_at' => $service->end_at->format('d.m.Y'),
            'requisites' => $service->requisites ? $service->requisites->name : null,
        ];
    }

    public function typePrograms(): JsonResponse
    {
        $regulars = ServiceProgram::query()->select('id')
            ->where('service_type_id', ServiceTypes::regular)
            ->get()
            ->pluck('id')
            ->toArray();
        $singleType = ServiceProgram::query()->select('id')
            ->where('service_type_id', ServiceTypes::one_time)
            ->get()
            ->pluck('id')
            ->toArray();

        $values = [
            'regulars' => $regulars,
            'singleType' => $singleType,
        ];

        return APIResponse::response($values);
    }
}
