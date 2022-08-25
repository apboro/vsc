<?php

namespace App\Http\Controllers\Leads;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Dictionaries\Discount;
use App\Models\Dictionaries\Region;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Services\Service;
use App\Models\Subscriptions\Subscription;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

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
            $subscription = Subscription::query()->where('id', $subscriptionId)->with(['client', 'clientWard', 'service'])->first();
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
                'ward_lastname' => $subscription->clientWard->user->profile->lastname,
                'ward_firstname' => $subscription->clientWard->user->profile->firstname,
                'ward_patronymic' => $subscription->clientWard->user->profile->patronymic,
                'ward_birth_date' => $subscription->clientWard->user->profile->birthdate->format('Y-m-d'),
            ];
            $serviceData = [
                'title' => $subscription->service->title,
                'sport_kind' => $subscription->service->sportKind->name,
                'training_base_title' => $subscription->service->trainingBase->title,
                'training_base_address' => $subscription->service->trainingBase->info->address,
                'schedule' => $subscription->service->schedule->text,
            ];
            $discounts = Discount::queryRaw()
                ->where(['organization_id' => $key['organization_id'], 'enabled' => true])
                ->select(['id', 'name', 'description as hint'])
                ->orderBy('order')
                ->get();
        } else {
            $key = LeadSession::getKey($request);
        }

        if ($key['organization_id'] === null) {
            return APIResponse::error('Ошибка сессии');
        }

        $regions = Region::queryRaw()
            ->where(['organization_id' => $key['organization_id'], 'enabled' => true])
            ->select(['id', 'name'])
            ->orderBy('order')
            ->get();

        $services = Service::query()
            ->leftJoin('training_bases', 'training_bases.id', '=', 'services.training_base_id')
            ->leftJoin('training_base_info', 'training_bases.id', '=', 'training_base_info.base_id')
            ->where(['services.organization_id' => $key['organization_id'], 'services.status_id' => ServiceStatus::enabled])
            ->select([
                'services.id',
                'services.title',
                DB::raw('IFNULL(training_bases.short_title, training_bases.title) as base'),
                'training_base_info.address',
                'training_bases.region_id',
            ])
            ->orderBy('services.title')
            ->get();

        return APIResponse::response([
            'session' => LeadSession::makeSession((int)$key['organization_id'], $request->ip()),
            'regions' => $regions,
            'services' => $services,
            'subscription_id' => $subscription->id ?? null,
            'subscription_data' => $subscriptionData ?? null,
            'service_data' => $serviceData ?? null,
            'discounts' => $discounts ?? null,
        ]);
    }
}
