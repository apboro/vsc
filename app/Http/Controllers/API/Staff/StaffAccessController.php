<?php

namespace App\Http\Controllers\API\Staff;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffAccessController extends ApiEditController
{
    protected array $rules = [
        'login' => 'required|min:6|unique:users',
        'password' => 'required|min:6',
        'password_confirmation' => 'required|same:password',
    ];

    protected array $titles = [
        'login' => 'Логин',
        'password' => 'Новый пароль',
        'password_confirmation' => 'Подтверждение пароля',
    ];

    /**
     * Release staff user access.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function release(Request $request): JsonResponse
    {
        if (($user = $this->getUser($request)) === null) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        /** @var User $user */
        if ($user->id === $request->user()->id) {
            return APIResponse::error('Вы не можете отключить себе доступ.');
        }

        $user->login = null;
        $user->password = null;
        $user->save();

        return APIResponse::success(
            'Доступ закрыт',
            [
                'has_access' => false,
                'login' => null,
            ]
        );
    }

    /**
     * Release staff user access.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function set(Request $request): JsonResponse
    {
        /** @var User $user */
        if (($user = $this->getUser($request)) === null) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        // Validate data
        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        $user->login = $data['login'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return APIResponse::success(
            'Доступ открыт',
            [
                'has_access' => true,
                'login' => $user->login,
            ]
        );
    }

    /**
     * Get user.
     *
     * @param Request $request
     *
     * @return  User|null
     */
    protected function getUser(Request $request): ?User
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var User $user */
        if ($id === null || null === ($user = User::query()
                ->where('id', $id)
                ->whereHas('position', function (Builder $query) use ($current) {
                    $query->tap(new ForOrganization($current->organizationId(), true));
                })
                ->first()
            )) {
            return null;
        }

        return $user;
    }
}
