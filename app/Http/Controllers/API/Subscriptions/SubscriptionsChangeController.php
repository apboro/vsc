<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Mail\SubscriptionContractFillLinkMail;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Services\Service;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionsChangeController extends ApiEditController
{
    protected array $rules = [
        'region_id' => 'nullable',
        'object_id' => 'nullable',
        'service_id' => 'required',
        'contract_comment' => 'nullable',
    ];

    protected array $titles = [
        'region_id' => 'Район',
        'object_id' => 'Объект',
        'service_id' => 'Услуга',
        'contract_comment' => 'Комментарий клиенту',
    ];

    public function get(Request $request): JsonResponse
    {
        $subscriptionId = $request->input('subscription_id');

        $current = Current::get($request);

        /** @var Subscription $subscription */
        if ($subscriptionId === null ||
            null === ($subscription = Subscription::query()
                ->with(['service.trainingBase'])
                ->where('id', $subscriptionId)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Подписка на услугу не найдена');
        }

        return APIResponse::form(
            [
                'region_id' => $subscription->service->trainingBase->region_id,
                'object_id' => $subscription->service->training_base_id,
                'service_id' => $subscription->service_id,
                'contract_comment' => null,
            ],
            $this->rules,
            $this->titles,
            [
                'services' => Service::query()
                    ->leftJoin('training_bases', 'training_bases.id', '=', 'services.training_base_id')
                    ->leftJoin('training_base_info', 'training_bases.id', '=', 'training_base_info.base_id')
                    ->where(['services.organization_id' => $current->organizationId(), 'services.status_id' => ServiceStatus::enabled])
                    ->select([
                        'services.id',
                        'services.title',
                        'services.training_base_id',
                        DB::raw('IFNULL(training_bases.short_title, training_bases.title) as base'),
                        'training_base_info.address',
                        'training_bases.region_id',
                    ])
                    ->orderBy('services.title')
                    ->get(),
            ]
        );
    }

    public function update(Request $request): JsonResponse
    {
        $subscriptionId = $request->input('subscription_id');

        $current = Current::get($request);

        /** @var Subscription $subscription */
        if ($subscriptionId === null ||
            null === ($subscription = Subscription::query()
                ->with(['service', 'status', 'client.user.profile', 'contracts'])
                ->where('id', $subscriptionId)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Подписка на услугу не найдена');
        }

        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        try {
            DB::transaction(static function () use ($subscription, $data, $current) {

                // create subscription
                $newSubscription = new Subscription();
                $newSubscription->setStatus(SubscriptionStatus::new, false);
                $newSubscription->organization_id = $current->organizationId();
                $newSubscription->client_id = $subscription->client_id;
                $newSubscription->client_ward_id = $subscription->client_ward_id;
                $newSubscription->service_id = $data['service_id'];
                $newSubscription->save();

                // send a link to client
                try {
                    Mail::send(new SubscriptionContractFillLinkMail($newSubscription, $data['contract_comment']));
                } catch (Exception $exception) {
                    Log::channel('outgoing_mail_errors')->error($exception->getMessage());
                    throw $exception;
                }

                $subscription->setStatus(SubscriptionStatus::closed);
                $subscription->contracts->map(function (SubscriptionContract $contract) {
                    if ($contract->hasStatus(SubscriptionContractStatus::accepted)) {
                        $contract->closed_at = Carbon::now();
                        $contract->setStatus(SubscriptionContractStatus::closed);
                    } else if ($contract->hasStatus(SubscriptionContractStatus::draft)) {
                        $contract->delete();
                    }
                });
            });
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Подписка заменена. Ссылка на заполнение договора отправлена клиенту.');
    }
}
