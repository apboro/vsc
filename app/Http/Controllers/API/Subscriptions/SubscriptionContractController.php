<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Mail\SubscriptionContractFillLinkMail;
use App\Mail\SubscriptionContractMail;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionContractController extends ApiController
{
    /**
     * Repeat contract sending.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        $contract = $this->contract($request, $request->input('id'));

        if ($contract === null) {
            return APIResponse::error('Документ не найден');
        }

        // send a link to client
        try {
            Mail::send(new SubscriptionContractMail($contract));
        } catch (Exception $exception) {
            Log::channel('outgoing_mail_errors')->error($exception->getMessage());
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Договор отправлен');
    }

    /**
     * Close contract.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function close(Request $request): JsonResponse
    {
        $contract = $this->contract($request, $request->input('id'));

        if ($contract === null) {
            return APIResponse::error('Документ не найден');
        }

        $contract->closed_at = Carbon::now();
        $contract->setStatus(SubscriptionContractStatus::closed, false);
        $contract->save();

        return APIResponse::success('Договор закрыт');
    }

    /**
     * Send link to fill contract.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function sendLink(Request $request): JsonResponse
    {
        $subscription = $this->subscription($request);

        if ($subscription === null) {
            return APIResponse::error('Подписка не найдена');
        }

        $subscription->setStatus(SubscriptionStatus::fill);

        // send a link to client
        try {
            Mail::send(new SubscriptionContractFillLinkMail($subscription));
        } catch (Exception $exception) {
            Log::channel('outgoing_mail_errors')->error($exception->getMessage());
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Ссылка отправлена');
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return SubscriptionContract|null
     */
    protected function contract(Request $request, int $id): ?SubscriptionContract
    {
        $current = Current::get($request);

        /** @var SubscriptionContract|null $contract */
        $contract = SubscriptionContract::query()
            ->with(['contractData'])
            ->where('id', $id)
            ->whereHas('subscription', function (Builder $query) use ($current) {
                $query->tap(new ForOrganization($current->organizationId()));
            })
            ->first();

        return $contract;
    }

    /**
     * @param Request $request
     *
     * @return SubscriptionContract|null
     */
    protected function subscription(Request $request): ?Subscription
    {
        $current = Current::get($request);

        /** @var Subscription|null $subscription */
        $subscription = Subscription::query()
            ->where('id', $request->input('subscription_id'))
            ->tap(new ForOrganization($current->organizationId()))
            ->first();

        return $subscription;
    }
}
