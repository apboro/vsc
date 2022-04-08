<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\PositionStatus;
use App\Models\Dictionaries\Role;
use App\Models\POS\Terminal;
use App\Models\User\Helpers\Currents;
use App\Models\User\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffPropertiesController extends ApiController
{
    /**
     * Update pier status.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function properties(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var User $user */
        if ($id === null || null === ($user = User::query()->where('id', $id)->first())) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        $name = $request->input('data.name');
        $value = $request->input('data.value');

        if (!in_array($name, ['status_id', 'roles'])) {
            return APIResponse::error('Неверно заданы параметры');
        }

        try {
            switch ($name) {
                case 'status_id':
                    // Check self change status
                    /** @var User $current */
                    $current = $request->user();
                    if ($current->position->id === $user->position->id && !$user->hasStatus((int)$value)) {
                        return APIResponse::error('Нельзя изменить свой статус трудоустройства.');
                    }
                    $user->staffPosition->setStatus((int)$value);
                    break;
                default:
            }
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::response([], [
            'status' => $user->staffPosition->status->name,
            'status_id' => $user->staffPosition->status_id,
            'active' => $user->staffPosition->hasStatus(PositionStatus::active),
            'roles' => $user->staffPosition->roles->pluck('id')->toArray(),
        ], "Данные сотрудника обновлёны");
    }
}
