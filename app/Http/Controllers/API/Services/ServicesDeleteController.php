<?php

namespace App\Http\Controllers\API\Services;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Services\Service;
use App\Scopes\ForOrganization;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicesDeleteController extends ApiController
{
    /**
     * Delete service.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->input('id');

        $current = Current::get($request);

        /** @var Service $service */
        if ($id === null || null === ($service = Service::query()->where('id', $id)->tap(new ForOrganization($current->organizationId()))->first())) {
            return APIResponse::notFound('Услуга не найдена');
        }

        $name = $service->title;

        try {
            $service->delete();
        } catch (QueryException $exception) {
            return APIResponse::error('Невозможно удалить услугу. Есть блокирующие связи.');
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success("Услуга \"$name\" удалена");
    }
}
