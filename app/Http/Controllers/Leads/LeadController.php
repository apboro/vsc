<?php

namespace App\Http\Controllers\Leads;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Middleware\LeadsProtect;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Leads\Lead;
use App\Models\Services\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LeadController extends ApiEditController
{
    /**
     * Handle requests to frontend index.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function init(Request $request): JsonResponse
    {
        $key = self::getKey($request);

        if ($key['organization_id'] === null) {
            return APIResponse::error('Ошибка сессии');
        }

        $services = Service::query()
            ->where(['organization_id' => $key['organization_id'], 'status_id' => ServiceStatus::enabled])
            ->select(['id', 'title'])
            ->orderBy('title')
            ->get();

        return APIResponse::response([
            'session' => self::makeSession((int)$key['organization_id'], $request->ip()),
            'services' => $services,
        ]);
    }

    /**
     * Add new lead.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        $organizationId = self::getOrganizationId($request);

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
            'service_id' => 'required',
        ];
        $titles = [
            'lastname' => 'Фамилия',
            'firstname' => 'Имя',
            'patronymic' => 'Отчество',
            'phone' => 'Телефон',
            'email' => 'Email',
            'service_id' => 'Услуга',
        ];
        if ($errors = $this->validate($data, $rules, $titles)) {
            return APIResponse::validationError($errors);
        }

        // add lead
        $lead = new Lead();

        $lead->lastname = mb_strtoupper(mb_substr($data['lastname'], 0, 1)) . mb_strtolower(mb_substr($data['lastname'], 1));
        $lead->firstname = mb_strtoupper(mb_substr($data['firstname'], 0, 1)) . mb_strtolower(mb_substr($data['firstname'], 1));
        $lead->patronymic = mb_strtoupper(mb_substr($data['patronymic'], 0, 1)) . mb_strtolower(mb_substr($data['patronymic'], 1));
        $lead->phone = $data['phone'];
        $lead->email = $data['email'];
        $lead->service_id = $data['service_id'];
        $lead->organization_id = $organizationId;
        $lead->status_id = LeadStatus::new;
        $lead->save();

        // response success
        return APIResponse::success('Ваша заявка отправлена.');
    }

    /**
     * Get parameters from request.
     *
     * @param Request $request
     *
     * @return  array
     */
    protected static function getKey(Request $request): array
    {
        if ($request->hasHeader('X-Vsc-Key')) {
            $key = Crypt::decrypt($request->header('X-Vsc-Key'));
        }

        return [
            'organization_id' => $key ?? null,
        ];
    }

    /**
     * Make encrypted session.
     *
     * @param int $organizationId
     * @param string $ip
     *
     * @return  string
     */
    protected static function makeSession(int $organizationId, string $ip): string
    {
        $session = [
            'organization_id' => $organizationId,
            'ip' => $ip,
        ];

        return Crypt::encrypt($session);
    }

    /**
     * Get organization ID.
     *
     * @param Request $request
     *
     * @return  int|null
     */
    protected static function getOrganizationId(Request $request): ?int
    {
        if ($request->hasHeader(LeadsProtect::HEADER_NAME)) {
            try {
                $session = Crypt::decrypt($request->header(LeadsProtect::HEADER_NAME));
            } catch (Exception $exception) {
                return null;
            }
        }

        return $session['organization_id'] ?? null;
    }
}
