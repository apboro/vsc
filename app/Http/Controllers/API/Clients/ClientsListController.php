<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Clients\Client;
use App\Models\Dictionaries\ClientStatus;
use App\Models\Leads\Lead;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientsListController extends ApiController
{
    protected array $defaultFilters = [
        'client_status_id' => ClientStatus::active,
    ];

    protected array $rememberFilters = [
        'client_status_id',
    ];

    protected string $rememberKey = CookieKeys::clients_list;

    /**
     * Get services list.
     *
     * @param APIListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $this->rememberKey = CookieKeys::getKey($this->rememberKey, $current->organizationId());

        $query = User::query()
            ->with(['profile', 'client', 'client.status'])
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('users.*')
            ->whereHas('client', function (Builder $query) use ($current) {
                $query->tap(new ForOrganization($current->organizationId(), true));
            })
            ->orderBy('user_profiles.lastname')
            ->orderBy('user_profiles.firstname')
            ->orderBy('user_profiles.patronymic');

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey)) && !empty($filters['client_status_id'])) {
            $query->whereHas('client', function (Builder $query) use ($filters) {
                $query->where('status_id', $filters['client_status_id']);
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
                'id' => $user->client->id,
                'name' => $user->profile->fullName,
                'email' => $user->profile->email,
                'phone' => $user->profile->phone,
                'status' => $user->client->status->name,
                'user_id' => $user->id,
            ];
        });

        return APIResponse::list(
            $users,
            ['ID', 'ФИО', 'Статус', 'Email', 'Телефон'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
