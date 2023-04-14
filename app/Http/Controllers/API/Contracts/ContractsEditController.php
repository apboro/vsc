<?php

namespace App\Http\Controllers\API\Contracts;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Pattern;
use App\Scopes\ForOrganization;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ContractsEditController extends ApiEditController
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
        $current = Current::get($request);

        $patternIDs = $request->input('patternIDs', []);

        try {
            DB::transaction(static function () use ($current, $patternIDs) {
                /** @var Collection<Contracts> $remove */
                $removeContracts = Contracts::query($current)->whereNotIn('pattern_id', $patternIDs)->get();

                foreach ($removeContracts as $contract) {
                    /** @var Contracts $contract */
                    try {
                        $contract->delete();
                    } catch (QueryException $exception) {
                        throw new RuntimeException(sprintf('Шаблон %s используется', $contract->name));
                    }
                }

                $registered = Contracts::query($current)->pluck('pattern_id')->toArray();

                $toRegister = array_diff($patternIDs, $registered);

                $patterns = Pattern::query()->whereIn('id', $toRegister)->get();

                $items = [];

                foreach ($patterns as $pattern) {
                    /** @var Pattern $pattern */
                    $contract = new Contracts();
                    $contract->name = $pattern->name;
                    $contract->pattern_id = $pattern->id;
                    $contract->organization_id = $current->organizationId();
                    $contract->save();
                }
            });
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Договоры обновлены');
    }
}
