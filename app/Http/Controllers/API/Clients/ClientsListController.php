<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Clients\ClientComment;
use App\Models\Clients\ClientWard;
use App\Models\Dictionaries\ClientStatus;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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

        $filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey);

        $query = $this->getListQuery($request, $filters, $current);

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
                'created_date' => $user->client->created_at->format('d.m.Y'),
                'user_id' => $user->id,
            ];
        });

        return APIResponse::list(
            $users,
            ['ID', 'ФИО', 'Дата создания', 'Статус', 'Контакты'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }

    public function export(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        $this->rememberKey = CookieKeys::getKey($this->rememberKey, $current->organizationId());

        $query = $this->getListQuery($request, [], $current);

        $users = $query->get();
        $now = Carbon::now();

        $titles = [
            'ID клиента',
            'ФИО клиента',
            'Дата создания',
            'Статус',
            'Телефон',
            'Email',
            'Виды спорта',
            'Объект',
            'Занимающийся',
            'Год рождения ребенка',
            'Комментарий',
        ];

        $transformedUsers = collect();

        /** @var User $user */
        foreach ($users as $user) {
            $sportKinds = '';
            $training_base = '';
            $subscriptions = $user->client->subscriptions;

            if (!empty($subscriptions)) {
                $arrTrainingBase = $subscriptions->pluck('service.trainingBase')->toArray();
                $training_base = $arrTrainingBase[0]['short_title'];

                $arrSportKinds = $subscriptions->pluck('service.sportKinds')->toArray();
                $result = [];
                array_walk_recursive($arrSportKinds, function($item, $key) use (&$result) {
                    if ($key === 'name') {
                        $result[] = $item;
                    }
                });
                $result = array_unique($result);
                $sportKinds = implode(', ', $result);
            }

            $wards = $user->client->wards;

            foreach ($wards as $ward) {
                $transformedUsers->add([
                    'id' => $user->id,
                    'name' => $user->profile->fullName,
                    'created_date' => $user->client->created_at->format('d.m.Y'),
                    'status' => $user->client->status->name,
                    'phone' => $user->profile->phone,
                    'email' => $user->profile->email,
                    'sportKinds' => $sportKinds,
                    'training_base' => $training_base,
                    'ward_name' => $ward->user->profile->fullName ?? null,
                    'ward_birth_dates' => ($ward->user->profile->birthdate ?? false) ? Carbon::parse($ward->user->profile->birthdate)->format('Y') : null,
                    'comment' => $user->client->comments->map(function (ClientComment $c) {
                        return $c->text;
                    })->join(', '),
                ]);
            }
        }

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0)->setTitle('Клиенты')->setShowRowColHeaders(true);

        $spreadsheet->getActiveSheet()->fromArray($titles, '—', 'A1');
        $spreadsheet->getActiveSheet()->fromArray($transformedUsers->toArray(), '—', 'A2');
        foreach(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'] as $col) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $export = ob_get_clean();

        return APIResponse::response([
            'file' => base64_encode($export),
            'file_name' => 'Клиенты ' . $now->format('Y-m-d H:i'),
            'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Make clients query with filters and search.
     *
     * @param APIListRequest $request
     * @param array $filters
     * @param Current $current
     *
     * @return  Builder
     */
    protected function getListQuery(ApiListRequest $request, array $filters, Current $current): Builder
    {
        $query = User::query()
            ->with(['profile', 'client', 'client.status', 'client.subscriptions.service.sportKinds'])
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('users.*')
            ->whereHas('client', function (Builder $query) use ($current) {
                $query->tap(new ForOrganization($current->organizationId(), true));
            })
            ->orderBy('user_profiles.lastname')
            ->orderBy('user_profiles.firstname')
            ->orderBy('user_profiles.patronymic');

        $filters = !empty($filters) ? $filters : $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey);

        // apply filters
        if (!empty($filters)) {
            if (!empty($filters['client_status_id'])) {
                $query->whereHas('client', function (Builder $query) use ($filters) {
                    $query->where('status_id', $filters['client_status_id']);
                });
            }
            if (!empty($filters['sport_kinds'])) {
                $query->whereHas('client.subscriptions.service.sportKinds', function (Builder $query) use ($filters) {
                    $query->whereIn('id', $filters['sport_kinds']);
                });
            }
            if (!empty($filters['training_base_id'])) {
                $query->whereHas('client.subscriptions.service', function (Builder $query) use ($filters) {
                    $query->whereIn('training_base_id', $filters['training_base_id']);
                });
            }
        }

        // apply search
        if (!empty($search = $request->search())) {
            foreach ($search as $term) {
                $query->where(function (Builder $query) use ($term) {
                    $query->whereHas('profile', function (Builder $query) use ($term) {
                        $query->where('lastname', 'LIKE', "%$term%")
                            ->orWhere('firstname', 'LIKE', "%$term%")
                            ->orWhere('patronymic', 'LIKE', "%$term%")
                            ->orWhere('phone', 'LIKE', "%$term%");
                    });
                });
            }
        }

        return  $query;
    }
}
