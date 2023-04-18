<?php

namespace App\Http\Controllers\LeadsSingle;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Dictionaries\Discount;
use App\Models\Dictionaries\Region;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\ServiceTypes;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Services\Service;
use App\Models\Services\ServiceProgram;
use App\Models\Subscriptions\Subscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class LeadSingleInitController extends ApiEditController
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
            if (!$subscription->hasStatus(SubscriptionStatus::new) && !$subscription->hasStatus(SubscriptionStatus::fill)) {
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
            $discounts = Discount::queryRaw()
                ->where(['organization_id' => $key['organization_id'], 'enabled' => true])
                ->select(['id', 'name', 'description as hint'])
                ->orderBy('order')
                ->get();

            $serviceData = [
                'title' => $subscription->service->title,
                'training_base_title' => $subscription->service->trainingBase->title,
                'training_base_address' => $subscription->service->trainingBase->info->address,
                'service_start_date' => self::formatDate($subscription->service->start_at, 'года'),
            ];
        } else {
            try {
                $key = LeadSession::getKey($request);
            } catch (Exception $exception) {
                return APIResponse::error('Ошибка параметра');
            }
        }

        if ($key['organization_id'] === null) {
            return APIResponse::error('Ошибка сессии');
        }

        $singleType = ServiceProgram::select('id')
            ->where('service_type_id', ServiceTypes::one_time)
            ->get()
            ->pluck('id')
            ->toArray();

        $services = Service::query()
            ->leftJoin('training_bases', 'training_bases.id', '=', 'services.training_base_id')
            ->leftJoin('training_base_info', 'training_bases.id', '=', 'training_base_info.base_id')
            ->where(['services.organization_id' => $key['organization_id'], 'services.status_id' => ServiceStatus::enabled])
            ->whereIn('type_program_id', $singleType)
            ->select([
                'services.id',
                'services.title',
                DB::raw('IFNULL(training_bases.short_title, training_bases.title) as base'),
                'training_base_info.address',
                'training_bases.region_id',
            ])
            ->orderBy('services.title')
            ->get();

        if (isset($services) && count($services) > 0) {
            $arrRegions = [];
            $regions = Region::queryRaw()
                ->whereIn('id', $arrRegions)
                ->where(['organization_id' => $key['organization_id'], 'enabled' => true])
                ->select(['id', 'name'])
                ->orderBy('order')
                ->get();
        }


        return APIResponse::response([
            'session' => LeadSession::makeSession((int)$key['organization_id'], $request->ip()),
            'regions' => $regions ?? null,
            'services' => $services,
            'subscription_data' => $subscriptionData ?? null,
            'subscription_id' => $subscription->id ?? null,
            'service_data' => $serviceData ?? null,
            'discounts' => $discounts ?? null,
        ]);
    }

    protected static function formatDate(?Carbon $date, string $suffix = null): string
    {
        if ($date === null) {
            return '*дата не назначена*';
        }

        return $date->translatedFormat('«j»') . ' ' . $date->getTranslatedMonthName('Do MMMM') . ' ' . $date->year . ($suffix ? ' ' . $suffix : '');
    }
}
