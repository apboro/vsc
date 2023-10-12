<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Clients\Client;
use App\Models\Clients\ClientComment;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientsCommentsListController extends ApiController
{
    protected string $rememberKey = CookieKeys::clients_comments_list;

    /**
     * Get clients wards list.
     *
     * @param APIListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var Client|null $client */
        $client = Client::query()
            ->where('id', $request->input('client_id'))
            ->tap(new ForOrganization($current->organizationId()))
            ->first();

        if ($client === null) {
            return APIResponse::error('Клиент не найден');
        }

        $query = $client->comments()
            ->with(['type', 'actionType', 'position'])
            ->orderBy('created_at', 'DESC');

        // current page automatically resolved from request via `page` parameter
        $wards = $query->paginate($request->perPage(10, $this->rememberKey));

        /** @var LengthAwarePaginator $wards */
        $wards->transform(function (ClientComment $comment) use ($current){
            return [
                'id' => $comment->id,
                'created_at' => $comment->created_at->format('d.m.Y'),
                'text' => $comment->text,
                'type' => $comment->type->name,
                'action_type' => $comment->actionType->name ?? null,
                'position' => $comment->position ? $comment->position->user->profile->fullName : null,
                'type_id' => $comment->type_id,
                'action_type_id' => $comment->action_id,
                'position_id' => $comment->position_id,
                'can_edit' => $comment->isInner() && $current->can('clients.edit'),
            ];
        });

        return APIResponse::list(
            $wards,
            ['Дата создания', 'Комментарий', 'Тип комментария', 'Связан с действием', 'Менеджер'],
            [],
            [],
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}