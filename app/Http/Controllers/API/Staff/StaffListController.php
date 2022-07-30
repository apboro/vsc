<?php

namespace App\Http\Controllers\API\Staff;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\PositionStatus;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffListController extends ApiController
{
    protected array $defaultFilters = [
        'position_status_id' => PositionStatus::active,
    ];

    protected array $rememberFilters = [
        'position_status_id',
    ];

    protected string $rememberKey = CookieKeys::staff_list;

    /**
     * Get positions list.
     *
     * @param ApiListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $query = User::query()
            ->with(['profile', 'position', 'position.info'])
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('users.*')
            ->whereHas('position', function (Builder $query) use ($current){
                $query->tap(new ForOrganization($current->organizationId(), true));
            })
            ->orderBy('user_profiles.lastname')
            ->orderBy('user_profiles.firstname')
            ->orderBy('user_profiles.patronymic');

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey)) && !empty($filters['position_status_id'])) {
            $query->whereHas('position', function (Builder $query) use ($filters) {
                $query->where('status_id', $filters['position_status_id']);
            });
        }

        // apply search
        if (!empty($search = $request->search())) {
            foreach ($search as $term) {
                $query->where(function (Builder $query) use ($term) {
                    $query->whereHas('profile', function (Builder $query) use ($term) {
                        $query->where('lastname', 'LIKE', "%$term%")
                            ->orWhere('firstname', 'LIKE', "%$term%")
                            ->orWhere('patronymic', 'LIKE', "%$term%");
                    });
                });
            }
        }

        // current page automatically resolved from request via `page` parameter
        $users = $query->paginate($request->perPage(10, $this->rememberKey));

        /** @var LengthAwarePaginator $users */
        $users->transform(function (User $user) {

            return [
                'id' => $user->id,
                'active' => $user->position->hasStatus(PositionStatus::active),
                'name' => $user->profile->fullName ?? null,
                'position' => $user->position->title->name ?? null,
                'email' => $user->profile->email,
                'work_phone' => $user->position->info->work_phone,
                'work_phone_add' => $user->position->info->work_phone_additional,
                'mobile_phone' => $user->profile->mobile_phone,
                'has_access' => !empty($user->login) && !empty($user->password) && $user->position->hasStatus(PositionStatus::active),
            ];
        });

        return APIResponse::list(
            $users,
            [
                'ФИО сотрудника', 'Должность', 'Email', 'Рабочий телефон', 'Мобильный телефон',
            ],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
