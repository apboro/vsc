<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Common\File;
use App\Models\TrainingBase\TrainingBase;
use App\Models\TrainingBase\TrainingBaseContract;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingBaseContractEditController extends ApiEditController
{
    protected array $rules = [
        'status_id' => 'required',
        'start_at' => 'required',
        'end_at' => 'required',
        'files' => 'required',
    ];

    protected array $titles = [
        'status_id' => 'Статус',
        'start_at' => 'Дата начала',
        'end_at' => 'Дата окончания',
        'files' => 'Документы',
    ];

    /**
     * Update or create training base contract.
     *
     * ID === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $baseId = $request->input('base_id');
        $current = Current::get($request);

        /** @var TrainingBase $base */
        if ($baseId === null ||
            null === ($base = TrainingBase::query()
                ->where('id', $baseId)
                ->tap(new ForOrganization($current->organizationId()))
                ->first())
        ) {
            return APIResponse::notFound('Объект не найден');
        }

        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        /** @var TrainingBaseContract|null $contract */
        $contract = $this->firstOrNew(TrainingBaseContract::class, $request);

        if (($id !== 0) && ($contract === null || $contract->training_base_id !== $base->id)) {
            return APIResponse::notFound('Документ не найден');
        }

        $contract->start_at = $data['start_at'];
        $contract->end_at = $data['end_at'];
        $contract->training_base_id = $base->id;
        $contract->setStatus($data['status_id']);
        $contract->save();

        $files = File::createFromMany($data['files'], 'training_base_contracts');
        $fileIds = $files->pluck('id')->toArray();
        $contract->files()->sync($fileIds);

        // send response
        return APIResponse::success($contract->wasRecentlyCreated ? 'Документ добавлен' : 'Данные документа обновлены');
    }
}
