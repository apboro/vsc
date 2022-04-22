<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Common\Image;
use App\Models\Dictionaries\SportKind;
use App\Models\Dictionaries\TrainingBaseStatus;
use App\Models\TrainingBase\TrainingBase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingBaseViewController extends ApiController
{
    public function view(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var TrainingBase $base */
        if ($id === null ||
            null === ($base = TrainingBase::query()
                ->with(['status', 'info'])
                ->where('id', $id)
                ->first())
        ) {
            return APIResponse::notFound('Объект не найен');
        }

        $values = [
            'status' => $base->status->name,
            'active' => $base->hasStatus(TrainingBaseStatus::enabled),
            'title' => $base->title,
            'short_title' => $base->short_title,
            'address' => $base->info->address,
            'email' => $base->info->email,
            'phone' => $base->info->phone,
            'description' => $base->info->description,
            'images' => $base->images->map(function (Image $image) {
                return $image->url;
            }),
            'sport_kinds' => $base->sportKinds->map(function (SportKind $kind) {
                return $kind->name;
            }),
        ];

        // send response
        return APIResponse::response($values);
    }
}
