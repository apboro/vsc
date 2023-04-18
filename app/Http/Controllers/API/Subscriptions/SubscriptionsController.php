<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionsController extends ApiController
{
    public function close(Request $request): JsonResponse
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

        if($subscription->hasStatus(SubscriptionStatus::closed)) {
            return APIResponse::error('Подписка уже закрыта');
        }

        try {
            DB::transaction(function () use ($subscription) {
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

        return APIResponse::success('Подписка закрыта');
    }
}
