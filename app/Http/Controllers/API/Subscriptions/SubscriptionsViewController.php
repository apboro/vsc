<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\Services\ServicesViewController;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Subscriptions\Subscription;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionsViewController extends ApiController
{
    public function view(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $clientId = $request->input('client_id');

        $current = Current::get($request);

        /** @var Subscription $subscription */
        if ($id === null ||
            null === ($subscription = Subscription::query()
                ->with(['service', 'status', 'client.user.profile'])
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId()))
                ->when($clientId, function (Builder $query) use ($clientId) {
                    $query->whereHas('client', function (Builder $query) use ($clientId) {
                        $query->where('id', $clientId);
                    });
                })
                ->first())
        ) {
            return APIResponse::notFound('Подписка на услугу не найдена');
        }

        $values = [
            'title' => "Подписка на услугу \"" . $subscription->service->title . "\"",
            'status' => $subscription->status->name,
            'client' => $subscription->client->user->profile->compactName,
            'client_id' => $subscription->client_id,
            'service' => ServicesViewController::composeServiceData($subscription->service),
            'is_closeable' => !$subscription->hasStatus(SubscriptionStatus::closed),
            'is_changeable' => !$subscription->hasStatus(SubscriptionStatus::closed),
            'is_repeatable' => !$subscription->hasStatus(SubscriptionStatus::closed),
        ];

        return APIResponse::response($values);
    }
}
