<?php

namespace App\Http\Controllers\API\Staff;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Positions\Position;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffEditController extends ApiEditController
{
    protected array $rules = [
        'last_name' => 'required',
        'first_name' => 'required',
        'status_id' => 'required',
        'position_title_id' => 'required',
        'email' => 'required|email|bail',
        'gender' => 'required',
        'birthdate' => 'date|nullable',
    ];

    protected array $titles = [
        'last_name' => 'Фамилия',
        'first_name' => 'Имя',
        'patronymic' => 'Отчество',
        'status_id' => 'Статус трудоустройства',
        'position_title_id' => 'Должность',

        'birthdate' => 'Дата рождения',
        'gender' => 'Пол',

        'email' => 'Email',
        'work_phone' => 'Рабочий телефон',
        'work_phone_additional' => 'добавочный',
        'phone' => 'Телефон',

        'notes' => 'Заметки',
    ];

    /**
     * Get edit data for user.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->firstOrNewUser($request, ['profile', 'position', 'position.status', 'position.info', 'position.title']);

        if ($user === null) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        // send response
        return APIResponse::form(
            [
                'last_name' => $user->profile->lastname,
                'first_name' => $user->profile->firstname,
                'patronymic' => $user->profile->patronymic,
                'status_id' => $user->position->status_id ?? null,
                'position_title_id' => $user->position->title_id ?? null,
                'birthdate' => $user->profile->birthdate ? $user->profile->birthdate->format('Y-m-d') : null,
                'gender' => $user->profile->gender,
                'email' => $user->profile->email,
                'work_phone' => $user->position->info->work_phone ?? null,
                'work_phone_additional' => $user->position->info->work_phone_additional ?? null,
                'phone' => $user->profile->phone,
                'notes' => $user->position->info->notes ?? null,
            ],
            $this->rules,
            $this->titles,
            [
                'name' => $user->exists ? $user->profile->fullName : 'Добавление сотрудника',
            ]
        );
    }

    /**
     * Update excursion data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $data = $this->getData($request);
        $current = Current::get($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        /** @var User|null $user */
        $user = $this->firstOrNewUser($request);

        if ($user === null) {
            return APIResponse::notFound('Сотрудник не найден');
        }

        if ($user->exists) {
            if ($user->id === $request->user()->id && !$user->position->hasStatus($data['status_id'])) {
                return APIResponse::validationError(['status_id' => ['Нельзя изменить свой статус трудоустройства.']]);
            }
        } else {
            $user->save();
        }

        $profile = $user->profile;
        $profile->lastname = $data['last_name'];
        $profile->firstname = $data['first_name'];
        $profile->patronymic = $data['patronymic'];
        $profile->birthdate = $data['birthdate'] === null ? null : Carbon::parse($data['birthdate'])->toDate();
        $profile->gender = $data['gender'];
        $profile->email = $data['email'];
        $profile->phone = $data['phone'];
        $profile->save();

        $position = $user->position === null ? new Position : $user->position;
        $position->user_id = $user->id;
        $position->setStatus($data['status_id'], false);
        $position->title_id = $data['position_title_id'];
        $position->organization_id = $current->organizationId();
        $position->save();

        $info = $position->info;
        $info->work_phone = $data['work_phone'];
        $info->work_phone_additional = $data['work_phone_additional'];
        $info->notes = $data['notes'];
        $info->save();

        return APIResponse::success(
            $user->wasRecentlyCreated ? 'Сотрудник добавлен' : 'Данные сотрудника обновлены',
            [
                'id' => $user->id,
                'name' => $profile->fullName,
            ]
        );
    }

    /**
     * Retrieve user by ID or create new.
     *
     * @param Request $request
     * @param array $with
     *
     * @return  User|null
     */
    protected function firstOrNewUser(Request $request, array $with = []): ?User
    {
        $current = Current::get($request);

        /** @var User $class */

        if (($id = $request->input('id')) === null) {
            return null;
        }

        $id = (int)$id;

        if ($id === 0) {
            return new User();
        }

        /** @var User $user */
        $user = User::query()
            ->where('id', $id)
            ->whereHas('position', function (Builder $query) use ($current) {
                $query->tap(new ForOrganization($current->organizationId(), true));
            })
            ->with($with)
            ->first();

        return $user ?? null;
    }
}
