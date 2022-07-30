<?php

namespace App\Http\Controllers\API\Staff;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffDeleteController extends ApiController
{
    /**
     * Delete staff user.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var User $user */
        if ($id === null || null === ($user = User::query()
                ->with('profile')
                ->where('id', $id)
                ->whereHas('position', function (Builder $query) use ($current) {
                    $query->tap(new ForOrganization($current->organizationId(), true));
                })
                ->first()
            )) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        if ($user->id === $request->user()->id) {
            return APIResponse::error('Вы не можете удалить свою учётную запись');
        }

        $name = $user->profile->fullName;

        try {
            $user->delete();
        } catch (QueryException $exception) {
            return APIResponse::error('Невозможно удалить сотрудника. Есть блокирующие связи.');
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success("Сотрудник \"$name\" удалён");
    }
}
