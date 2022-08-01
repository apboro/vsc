<?php

namespace App\Http\Controllers\API\Services;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Service\Service;
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

        $values = [
            'status' => $service->status->name,
            'active' => $service->hasStatus(ServiceStatus::enabled),
            'title' => $service->title,
            'training_base' => $service->trainingBase->title,
            'sport_kind' => $service->sportKind->name,
            'schedule' => $service->schedule->text,
        ];

        // send response
        return APIResponse::response($values);
    }
}
