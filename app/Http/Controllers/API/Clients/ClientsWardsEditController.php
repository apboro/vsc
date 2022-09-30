<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Clients\ClientWard;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsWardsEditController extends ApiEditController
{
    protected array $rules = [
        'ward_lastname' => 'required',
        'ward_firstname' => 'required',
        'ward_patronymic' => 'required',
        'ward_birth_date' => 'required',
    ];

    protected array $titles = [
        'ward_lastname' => 'Фамилия',
        'ward_firstname' => 'Имя',
        'ward_patronymic' => 'Отчество',
        'ward_birth_date' => 'Дата рождения',
    ];

    /**
     * Get edit data for ward.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var ClientWard|null $ward */
        $ward = ClientWard::query()
            ->with(['user.profile'])
            ->where('id', $request->input('ward_id'))
            ->whereHas('clients', function (Builder $query) use ($request, $current) {
                $query
                    ->where('id', $request->input('client_id'))
                    ->tap(new ForOrganization($current->organizationId(), true));
            })
            ->first();

        if ($ward === null) {
            return APIResponse::notFound('Занимающийся не найден');
        }

        $values = [
            'ward_lastname' => $ward->user->profile->lastname,
            'ward_firstname' => $ward->user->profile->firstname,
            'ward_patronymic' => $ward->user->profile->patronymic,
            'ward_birth_date' => $ward->user->profile->birthdate->format('Y-m-d'),
        ];

        // send response
        return APIResponse::form(
            $values,
            $this->rules,
            $this->titles
        );
    }

    /**
     * Update ward data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var ClientWard|null $ward */
        $ward = ClientWard::query()
            ->with(['user.profile'])
            ->where('id', $request->input('ward_id'))
            ->whereHas('clients', function (Builder $query) use ($request, $current) {
                $query
                    ->where('id', $request->input('client_id'))
                    ->tap(new ForOrganization($current->organizationId(), true));
            })
            ->first();

        if ($ward === null) {
            return APIResponse::notFound('Занимающийся не найден');
        }

        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        $profile = $ward->user->profile;
        $profile->lastname = $data['ward_lastname'];
        $profile->firstname = $data['ward_firstname'];
        $profile->patronymic = $data['ward_patronymic'];
        $profile->birthdate = Carbon::parse($data['ward_birth_date']);
        $profile->save();

        return APIResponse::success('Данные занимающегося обновлены');
    }
}
