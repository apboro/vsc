<?php

namespace App\Http\Controllers\API\Leads;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Dictionaries\ClientCommentActionType;
use App\Models\Leads\Lead;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadsCommentController extends ApiEditController
{
    public function comment(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $current = Current::get($request);

        /** @var Lead $lead */
        if ($id === null ||
            null === ($lead = Lead::query()
                ->where('id', $id)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Лид не найден');
        }

        $data = $this->getData($request);

        $lead->comments = $data['comments'] ?? null;

        DB::transaction(function () use ($lead, $data) {
            if ($lead->subscription && $lead->subscription->client) {
                $lead->subscription->client->addcomment($data['comments'], ClientCommentActionType::lead_card);
            }

            $lead->save();
        });

        // send response
        return APIResponse::success('Комментарий обновлён');
    }
}
