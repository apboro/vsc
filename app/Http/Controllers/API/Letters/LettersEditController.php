<?php

namespace App\Http\Controllers\API\Letters;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Dictionaries\Letters;
use App\Models\Dictionaries\PatternLetters;
use App\Scopes\ForOrganization;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LettersEditController extends ApiEditController
{
    protected array $rules = [
        'name' => 'required',
        'pattern_id' => 'required',
        'organization_id' => 'required',
    ];

    protected array $titles = [
        'name' => 'Название договора',
        'pattern_id' => 'Шаблон',
        'organization_id' => 'Организация',
    ];

    /**
     * Update excursion data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $patternIDs = $request['patternIDs'];
        $organization_id = $request['organization_id'];

        try {
            DB::transaction(static function () use ($patternIDs, $organization_id) {
                /** @var Collection<Letters> $remove */
                $removeLetters = Letters::queryRaw()->tap(new ForOrganization($organization_id, true))->whereNotIn('pattern_id', $patternIDs)->get();

                foreach ($removeLetters as $letter) {
                    /** @var Letters $letter */
                    try {
                        $letter->delete();
                    } catch (QueryException $exception) {
                        throw new RuntimeException(sprintf('Шаблон %s используется', $letter->name));
                    }
                }

                $registered = Letters::queryRaw()->tap(new ForOrganization($organization_id, true))->pluck('pattern_id')->toArray();

                $toRegister = array_diff($patternIDs, $registered);

                $patterns = PatternLetters::query()->whereIn('id', $toRegister)->get();

                foreach ($patterns as $pattern) {
                    /** @var Letters $letter */
                    $letter = new Letters();
                    $letter->name = $pattern->name;
                    $letter->pattern_id = $pattern->id;
                    $letter->organization_id = $organization_id;
                    $letter->save();
                }
            });
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Письма обновлены');
    }
}
