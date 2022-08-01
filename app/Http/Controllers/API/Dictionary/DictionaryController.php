<?php

namespace App\Http\Controllers\API\Dictionary;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\AbstractDictionary;
use App\Models\Dictionaries\OrganizationStatus;
use App\Models\Dictionaries\PositionStatus;
use App\Models\Dictionaries\PositionTitle;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\SportKind;
use App\Models\Dictionaries\TrainingBaseContractStatus;
use App\Models\Dictionaries\TrainingBaseStatus;
use App\Models\Dictionaries\UserStatus;
use App\Models\TrainingBase\TrainingBase;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DictionaryController extends ApiController
{
    protected array $dictionaries = [
        'position_statuses' => ['class' => PositionStatus::class, 'allow' => null],
        'position_titles' => ['class' => PositionTitle::class, 'allow' => null],
        'user_statuses' => ['class' => UserStatus::class, 'allow' => null],
        'service_statuses' => ['class' => ServiceStatus::class, 'allow' => null],
        'sport_kinds' => ['class' => SportKind::class, 'allow' => null],
        'training_base_statuses' => ['class' => TrainingBaseStatus::class, 'allow' => null],
        'training_base_contract_statuses' => ['class' => TrainingBaseContractStatus::class, 'allow' => null],
        'organization_statuses' => ['class' => OrganizationStatus::class, 'allow' => null],
        'training_bases' => ['class' => TrainingBase::class, 'allow' => null],
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

        $current = Current::get($request);

        if (method_exists($class, 'asDictionary')) {
            $query = $class::asDictionary($current);
        } else {
            $query = $class::query($current);
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
