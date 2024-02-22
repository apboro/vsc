<?php

namespace App\Http\Controllers\LeadsGroup;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Leads\Lead;
use App\Models\Leads\LeadGroupData;
use App\Models\TrainingBase\TrainingBase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadGroupNewController extends ApiEditController
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
            'lastname' => 'required_without:organization_name',
            'firstname' => 'required_without:organization_name',
            'patronymic' => 'required_without:organization_name',
            'phone' => 'required_without:organization_name',
            'email' => 'required_without:organization_name|email|bail',
            'is_contract_individual' => 'nullable|bool',
            'organization_name' => 'nullable',
            'girls_1_count' => 'nullable|integer|gte:0',
            'boys_1_count' => 'nullable|integer|gte:0',
            'girls_2_count' => 'nullable|integer|gte:0',
            'boys_2_count' => 'nullable|integer|gte:0',
            'girls_3_count' => 'nullable|integer|gte:0',
            'boys_3_count' => 'nullable|integer|gte:0',
            'ward_count' => 'required|integer|gt:0',
            'trainer_count' => 'required|integer|gte:0',
            'attendant_count' => 'required|integer|gte:0',

            'region_id' => 'nullable',
            'service_id' => 'nullable',
            'clients_comments' => 'nullable',
            'need_help' => 'nullable',
        ];
        $titles = [
            'lastname' => 'Фамилия (законного представителя)',
            'firstname' => 'Имя (законного представителя)',
            'patronymic' => 'Отчество (законного представителя)',
            'phone' => 'Телефон',
            'email' => 'Email',
            'organization_name' => 'Наименование организации',

            'region_id' => 'Район',
            'service_id' => 'Услуга',
            'clients_comments' => 'Комментарии или дополнительные пожелания',
        ];
        if ($errors = $this->validate($data, $rules, $titles)) {
            return APIResponse::validationError($errors);
        }
        if (
            (int)$data['girls_1_count'] + (int)$data['boys_1_count'] +
            (int)$data['girls_2_count'] + (int)$data['boys_2_count'] +
            (int)$data['girls_3_count'] + (int)$data['boys_3_count'] !==
            (int)$data['ward_count']
        ) {
            return APIResponse::error('Общее количество детей не совпадает');
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
        if ($data['region_id'] === null && $data['service_id'] !== null) {
            $lead->region_id = TrainingBase::query()
                ->whereHas('services', function (Builder $query) use ($data) {
                    $query->where('id', $data['service_id']);
                })
                ->value('region_id');
        } else {
            $lead->region_id = $data['region_id'];
        }
        $lead->need_help = $data['need_help'];
        $lead->service_id = $data['service_id'];
        $lead->client_comments = $data['client_comments'];

        $leadGroupData = new LeadGroupData();
        $leadGroupData->organization_name = $data['organization_name'];
        $leadGroupData->is_contract_individual = $data['is_contract_individual'] ?? false;
        $leadGroupData->girls_1_count = $data['girls_1_count'];
        $leadGroupData->boys_1_count = $data['boys_1_count'];
        $leadGroupData->girls_2_count = $data['girls_2_count'];
        $leadGroupData->boys_2_count = $data['boys_2_count'];
        $leadGroupData->girls_3_count = $data['girls_3_count'];
        $leadGroupData->boys_3_count = $data['boys_3_count'];
        $leadGroupData->ward_count = $data['ward_count'];
        $leadGroupData->trainer_count = $data['trainer_count'];
        $leadGroupData->attendant_count = $data['attendant_count'];

        DB::transaction(function () use ($lead, $leadGroupData) {
            $lead->save();
            $lead->groupData()->save($leadGroupData);
        });

        // response success
        return APIResponse::success(
            'Благодарим за заявку! В течение одного дня Вы получите ответ на указанную электронную почту или с вами свяжется менеджер для уточнения деталей.'
        );
    }
}
