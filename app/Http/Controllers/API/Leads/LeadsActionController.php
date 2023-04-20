<?php


namespace App\Http\Controllers\API\Leads;


use App\Current;
use App\Http\APIResponse;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Leads\Lead;
use Illuminate\Http\JsonResponse;

class LeadsActionController
{


    public function delete(APIListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var Lead|null $lead */
        $lead = Lead::query()
            ->where('organization_id', $current->organizationId())
            ->where('id', $request->get('lead_id'))
            ->first();

        if(!$lead) {
            APIResponse::notFound('');
        }

        if ($lead->canDelete($current->position())) {
            $lead->setStatus(LeadStatus::deleted, true);
        }

        return APIResponse::success('Лид успешно удален');
    }
}
