<?php

namespace App\Http\Controllers\API\Dictionary;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\AbstractDictionary;
use App\Models\Dictionaries\PositionStatus;
use App\Models\Dictionaries\PositionTitle;
use App\Models\Dictionaries\UserStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DictionaryController extends ApiController
{
    protected array $dictionaries = [
        'position_statuses' => ['class' => PositionStatus::class, 'allow' => null],
        'position_titles' => ['class' => PositionTitle::class, 'allow' => null],
        'user_statuses' => ['class' => UserStatus::class, 'allow' => null],
    ];

    /**
     * Get dictionary.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function getDictionary(Request $request): JsonResponse
    {
        $name = $request->input('dictionary');

        if ($name === null || !array_key_exists($name, $this->dictionaries)) {
            return APIResponse::notFound("Справочник $name не найден");
        }

        $dictionary = $this->dictionaries[$name];

        if (array_key_exists('allow', $dictionary) && !$this->isAllowed($dictionary['allow'])) {
            return APIResponse::forbidden("Нет прав на просмотр справочника $name");
        }

        /** @var AbstractDictionary $class */
        $class = $dictionary['class'];

        if (method_exists($class, 'asDictionary')) {
            $query = $class::asDictionary();
        } else {
            $query = $class::query();
        }
        $actual = $query->clone()->latest('updated_at')->value('updated_at');
        $actual = Carbon::parse($actual)->setTimezone('GMT');

        $requested = $request->hasHeader('If-Modified-Since') ?
            Carbon::createFromFormat('D\, d M Y H:i:s \G\M\T', $request->header('If-Modified-Since'), 'GMT')
            : null;

        if ($requested >= $actual) {
            return APIResponse::notModified();
        }

        $dictionary = $query->orderBy('order')->orderBy('name')->get();

        return APIResponse::response($dictionary, null, null, $actual);
    }

    /**
     * Check ability to view dictionary.
     *
     * @param string|null $abilities
     *
     * @return  bool
     */
    public function isAllowed(?string $abilities): bool
    {
        if (empty($abilities)) {
            return true;
        }

        // TODO check proper rights

        return false;
    }
}
