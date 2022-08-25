<?php

namespace App\Http\Controllers\API\Services;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Services\Service;
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
            'schedule_days' => implode(
                ', ',
                array_filter([
                    $service->schedule->mon ? 'пн' : null,
                    $service->schedule->tue ? 'вт' : null,
                    $service->schedule->wed ? 'ср' : null,
                    $service->schedule->thu ? 'чт' : null,
                    $service->schedule->fri ? 'пт' : null,
                    $service->schedule->sat ? 'вб' : null,
                    $service->schedule->sun ? 'вс' : null,
                ], function ($day) {
                    return $day !== null;
                })
            ),
            'schedule_start_time' => $service->schedule->start_time ? $service->schedule->start_time->format('H:i') : null,
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
}
