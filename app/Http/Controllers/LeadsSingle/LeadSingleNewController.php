<?php

namespace App\Http\Controllers\LeadsSingle;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Leads\Lead;
use App\Models\TrainingBase\TrainingBase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadSingleNewController extends ApiEditController
{
    /**
     * Add new lead.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        $organizationId = LeadSession::getOrganizationId($request);

        if ($organizationId === null) {
            return APIResponse::error('Ошибка сессии.', ['oid is null']);
        }

        $data = $this->getData($request);

        // validate
        $rules = [
            'lastname' => 'required',
            'firstname' => 'required',
            'patronymic' => 'required',
            'phone' => 'required',
            'email' => 'required|email|bail',
            'ward_lastname' => 'required',
            'ward_firstname' => 'required',
            'ward_patronymic' => 'required',
            'ward_birth_date' => 'required',
            'ward_inv' => 'nullable',
            'ward_hro' => 'nullable',
            'ward_uch' => 'nullable',
            'ward_spe' => 'nullable',
            'region_id' => 'nullable',
            'service_id' => 'nullable',
            'need_help' => 'nullable',
        ];
        $titles = [
            'lastname' => 'Фамилия (законного представителя)',
            'firstname' => 'Имя (законного представителя)',
            'patronymic' => 'Отчество (законного представителя)',
            'phone' => 'Телефон',
            'email' => 'Email',
            'ward_lastname' => 'Фамилия (будущего чемпиона)',
            'ward_firstname' => 'Имя (будущего чемпиона)',
            'ward_patronymic' => 'Отчество (будущего чемпиона)',
            'ward_birth_date' => 'Дата рождения (будущего чемпиона)',
            'ward_inv' => 'Наличие у воспитанника инвалидности',
            'ward_hro' => 'Наличие у воспитанника хронических заболеваний',
            'ward_uch' => 'Состоит ли воспитанник на учете у медицинских специалистов',
            'ward_spe' => 'Индивидуальные особенности воспитанника (физические, психологические)',
            'region_id' => 'Район',
            'service_id' => 'Услуга',
            'need_help' => 'Нужна помощь',
        ];
        if ($errors = $this->validate($data, $rules, $titles)) {
            return APIResponse::validationError($errors);
        }

        // add lead
        $lead = new Lead();
        $lead->organization_id = $organizationId;
        $lead->status_id = LeadStatus::new;

        $lead->lastname = mb_strtoupper(mb_substr(trim($data['lastname']), 0, 1)) . mb_strtolower(mb_substr(trim($data['lastname']), 1));
        $lead->firstname = mb_strtoupper(mb_substr(trim($data['firstname']), 0, 1)) . mb_strtolower(mb_substr(trim($data['firstname']), 1));
        $lead->patronymic = mb_strtoupper(mb_substr(trim($data['patronymic']), 0, 1)) . mb_strtolower(mb_substr(trim($data['patronymic']), 1));
        $lead->phone = $data['phone'];
        $lead->email = trim($data['email']);
        $lead->ward_lastname = mb_strtoupper(mb_substr(trim($data['ward_lastname']), 0, 1)) . mb_strtolower(mb_substr(trim($data['ward_lastname']), 1));
        $lead->ward_firstname = mb_strtoupper(mb_substr(trim($data['ward_firstname']), 0, 1)) . mb_strtolower(mb_substr(trim($data['ward_firstname']), 1));
        $lead->ward_patronymic = mb_strtoupper(mb_substr(trim($data['ward_patronymic']), 0, 1)) . mb_strtolower(mb_substr(trim($data['ward_patronymic']), 1));
        $lead->ward_birth_date = Carbon::parse($data['ward_birth_date']);
        $lead->ward_inv = $data['ward_inv'];
        $lead->ward_hro = $data['ward_hro'];
        $lead->ward_uch = $data['ward_uch'];
        $lead->ward_spe = $data['ward_spe'];
        if ($data['region_id'] === null && $data['service_id'] !== null) {
            $lead->region_id = TrainingBase::query()
                ->whereHas('services', function (Builder $query) use ($data) {
                    $query->where('id', $data['service_id']);
                })
                ->value('region_id');
        } else {
            $lead->region_id = $data['region_id'];
        }
        $lead->service_id = $data['service_id'];
        $lead->need_help = $data['need_help'];
        $lead->client_comments = $data['client_comments'];

        $lead->save();

        // response success
        return APIResponse::success(
            'Благодарим за заявку! В течение одного дня Вы получите ответ на указанную электронную почту или с вами свяжется менеджер для уточнения деталей.'
        );
    }
}
