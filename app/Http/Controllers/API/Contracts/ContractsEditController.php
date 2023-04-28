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
        $patternIDs = $request->input('patternIDs', []);
        $organization_id = $request['organization_id'];

        try {
            DB::transaction(static function () use ($patternIDs, $organization_id) {
                /** @var Collection<Contracts> $remove */
                $removeContracts = Contracts::queryRaw()->tap(new ForOrganization($organization_id, true))->whereNotIn('pattern_id', $patternIDs)->get();

                foreach ($removeContracts as $contract) {
                    /** @var Contracts $contract */
                    try {
                        $contract->delete();
                    } catch (QueryException $exception) {
                        throw new RuntimeException(sprintf('Шаблон %s используется', $contract->name));
                    }
                }

                $registered = Contracts::queryRaw()->tap(new ForOrganization($organization_id, true))->pluck('pattern_id')->toArray();

                $toRegister = array_diff($patternIDs, $registered);

                $patterns = Pattern::query()->whereIn('id', $toRegister)->get();

                foreach ($patterns as $pattern) {
                    /** @var Pattern $pattern */
                    $contract = new Contracts();
                    $contract->name = $pattern->name;
                    $contract->pattern_id = $pattern->id;
                    $contract->organization_id = $organization_id;
                    $contract->save();
                }
            });
        } catch (Exception $exception) {
            return APIResponse::error($exception->getMessage());
        }

        return APIResponse::success('Договоры обновлены');
    }
}
