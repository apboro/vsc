<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Common\Image;
use App\Models\TrainingBase\TrainingBase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingBaseEditController extends ApiEditController
{
    protected array $rules = [
        'status_id' => 'required',
        'title' => 'required',
        'short_title' => 'required',
        'address' => 'required',
        'sport_kinds' => 'required',
        'images' => 'required',
        'email' => 'required|email|bail',
        'phone' => 'required',
        'description' => 'nullable',
    ];

    protected array $titles = [
        'status_id' => 'Статус объекта',
        'title' => 'Название',
        'short_title' => 'Короткое название',
        'sport_kinds' => 'Виды спорта',
        'images' => 'Изображения',
        'address' => 'Адрес',
        'email' => 'Email',
        'phone' => 'Телефон',
        'description' => 'Описание',
    ];

    /**
     * Get edit data for training base.
     *
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        /** @var TrainingBase|null $base */
        $base = $this->firstOrNew(TrainingBase::class, $request, ['status', 'info']);

        if ($base === null) {
            return APIResponse::notFound('Объект не найен');
        }

        // send response
        return APIResponse::form(
            [
                'status_id' => $base->status_id,
                'title' => $base->title,
                'short_title' => $base->short_title,
                'address' => $base->info->address,
                'email' => $base->info->email,
                'phone' => $base->info->phone,
                'description' => $base->info->description,
                'images' => $base->images->map(function (Image $image) {
                    return ['id' => $image->id, 'url' => $image->url];
                }),
                'sport_kinds' => $base->sportKinds->pluck('id'),
            ],
            $this->rules,
            $this->titles,
            [
                'title' => $base->exists ? $base->title : 'Добавление объекта',
            ]
        );
    }

    /**
     * Update training base data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        /** @var TrainingBase|null $base */
        $base = $this->firstOrNew(TrainingBase::class, $request, ['info']);

        if ($base === null) {
            return APIResponse::notFound('Объект не найен');
        }

        $base->title = $data['title'];
        $base->short_title = $data['short_title'];
        $base->setStatus($data['status_id']);
        $base->save();

        $base->sportKinds()->sync($data['sport_kinds']);

        $images = Image::createFromMany($data['images'], 'public_images');
        $imageIds = $images->pluck('id')->toArray();
        $base->images()->sync($imageIds);

        $base->info->address = $data['address'];
        $base->info->email = $data['email'];
        $base->info->phone = $data['phone'];
        $base->info->description = $data['description'];
        $base->info->save();

        return APIResponse::success(
            $base->wasRecentlyCreated ? 'Объект добавлен' : 'Данные объекта обновлены',
            [
                'id' => $base->id,
                'title' => $base->title,
            ]
        );
    }
}
