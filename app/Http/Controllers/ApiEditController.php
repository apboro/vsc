<?php

namespace App\Http\Controllers;

use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiEditController extends ApiController
{
    /**
     * Retrieve model by an ID or create new.
     *
     * @param string $class
     * @param Request $request
     * @param array $with
     * @param array $withCount
     * @param array $where
     *
     * @return  Model|null
     */
    protected function firstOrNew(string $class, Request $request, array $with = [], array $withCount = [], array $where = []): ?Model
    {
        /** @var Model $class */

        if (($id = $request->input('id')) === null) {
            return null;
        }

        $id = (int)$id;

        if ($id === 0 || $id === -1) {
            return new $class;
        }


        /** @var Model $model */
        $model = $class::query()->where('id', $id)->where($where)->with($with)->withCount($withCount)->first();

        return $model ?? null;
    }

    /**
     * Get data from request.
     *
     * @param Request $request
     *
     * @return  array
     */
    protected function getData(Request $request): array
    {
        return $request->input('data', []);
    }

    /**
     * Validate data and return validation errors.
     *
     * @param array $data
     * @param array $rules
     * @param array $titles
     *
     * @return  array|null
     */
    protected function validate(array $data, array $rules, array $titles): ?array
    {
        $validator = Validator::make($data, $rules, [], array_map(static function ($title) {
            return '"' . strtolower($title) . '"';
        }, $titles));

        return $validator->fails() ? $validator->getMessageBag()->toArray() : null;
    }
}
