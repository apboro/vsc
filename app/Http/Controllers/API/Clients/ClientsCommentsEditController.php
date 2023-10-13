<?php

namespace App\Http\Controllers\API\Clients;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Clients\ClientComment;
use App\Models\Dictionaries\ClientCommentType;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsCommentsEditController extends ApiEditController
{
    protected array $rules = [
        'text' => 'required|string',
    ];

    protected array $titles = [
        'text' => 'Комментарий',
    ];

    /**
     * Get edit data for comment.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var ClientComment|null $comment */
        $comment = ClientComment::query()
            ->where('id', $request->input('comment_id'))
            ->whereHas('client', function (Builder $query) use ($request, $current) {
                $query
                    ->tap(new ForOrganization($current->organizationId(), true));
            })
            ->first();

        $values = [
            'text' => $comment ? $comment->text : null,
            'position_id' => $comment ? $comment->position_id : null,
        ];

        // send response
        return APIResponse::form(
            $values,
            $this->rules,
            $this->titles
        );
    }

    /**
     * Update comment.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var ClientComment $comment */
        if ($request->input('comment_id')) {
            $comment = ClientComment::query()
            ->where('id', $request->input('comment_id'))
            ->whereHas('client', function (Builder $query) use ($request, $current) {
                $query
                    ->tap(new ForOrganization($current->organizationId(), true));
            })
            ->first();
        } else {
            $comment = new ClientComment();
        }

        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        $comment->text = $data['text'];
        $comment->client_id = $request->input('client_id');
        $comment->position_id = $current->positionId();
        $comment->type_id = ClientCommentType::inner;

        $comment->save();

        return APIResponse::success('Комментарий сохранен');
    }

    /**
     * Delete comment
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $commentId = $request->input('comment_id');

        if ($commentId === null) {
            return APIResponse::notFound('Комментарий не найден');
        }

        $comment = ClientComment::query()->find($commentId);

        if ($comment === null) {
            return APIResponse::notFound('Комментарий не найден');
        }

        $comment->delete();

        return APIResponse::success('Комментарий удален');
    }
}