<?php

namespace App\Http\Controllers\API\Organizations;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Organization\Organization;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationDeleteController extends ApiController
{
    /**
     * Delete organization.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var Organization $organization */
        if ($id === null || null === ($organization = Organization::query()->where('id', $id)->first())) {
            return APIResponse::notFound('Организация не найдена');
        }

        $title = $organization->title;

        try {
            $organization->delete();
        } catch (QueryException $exception) {
            return APIResponse::error('Невозможно удалить организацию. Есть блокирующие связи.');
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success("Организация \"$title\" удалена");
    }
}
