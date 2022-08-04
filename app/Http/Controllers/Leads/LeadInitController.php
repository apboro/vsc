<?php

namespace App\Http\Controllers\Leads;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Services\Service;
use App\Models\Subscriptions\Subscription;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LeadInitController extends ApiEditController
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
        $subscriptionKey = $request->input('subscription_key');
        /** @var Subscription|null $subscription */
        $subscription = null;

        if (!empty($subscriptionKey)) {
            try {
                $subscriptionId = Crypt::decrypt($subscriptionKey);
            } catch (Exception $exception) {
                return APIResponse::error('Ошибка параметров.');
            }
            /** @var Subscription|null $subscription */
            $subscription = Subscription::query()->where('id', $subscriptionId)->first();
            if ($subscription === null) {
                return APIResponse::error('Ошибка параметров.');
            }
            if (!$subscription->hasStatus(SubscriptionStatus::new)) {
                return APIResponse::error('Форма уже заполнена.');
            }
            $key['organization_id'] = $subscription->organization_id;
            $subscriptionData = [
                'lastname' => $subscription->client->user->profile->lastname,
                'firstname' => $subscription->client->user->profile->firstname,
                'patronymic' => $subscription->client->user->profile->patronymic,
                'phone' => $subscription->client->user->profile->phone,
                'email' => $subscription->client->user->profile->email,
            ];
            $serviceData = [
                'title' => $subscription->service->title,
                'sport_kind' => $subscription->service->sportKind->name,
                'training_base_title' => $subscription->service->trainingBase->title,
                'training_base_address' => $subscription->service->trainingBase->info->address,
                'schedule' => $subscription->service->schedule->text,
            ];
        } else {
            $key = self::getKey($request);
        }

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
            'subscription_id' => $subscription->id ?? null,
            'subscription_data' => $subscriptionData ?? null,
            'service_data' => $serviceData ?? null,
        ]);
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
}
