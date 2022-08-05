<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Helpers\SubscriptionContractPdf;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionContractViewController extends ApiController
{
    /**
     * Download contract.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function download(Request $request): JsonResponse
    {
        $contract = $this->contract($request, $request->input('id'));

        if ($contract === null) {
            return APIResponse::error('Документ не найден');
        }

        $pdf = SubscriptionContractPdf::generate($contract);

        return APIResponse::response([
            'contract' => base64_encode($pdf),
            'file_name' => "Договор.pdf",
        ]);
    }

    /**
     * Direct view.
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse|Response
     */
    public function view(Request $request, int $id)
    {
        $contract = $this->contract($request, $id);

        if ($contract === null) {
            return APIResponse::error('Документ не найден');
        }

        $pdf = SubscriptionContractPdf::generate($contract);

        return response($pdf, 200, [
            'Cache-Control' => 'public',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"Договор.pdf\"",
        ]);
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
}
