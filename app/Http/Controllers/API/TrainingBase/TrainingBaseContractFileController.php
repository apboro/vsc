<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Current;
use App\Http\Controllers\ApiController;
use App\Models\Common\File;
use App\Models\TrainingBase\TrainingBase;
use App\Scopes\ForOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TrainingBaseContractFileController extends ApiController
{
    /**
     * Download partner document.
     *
     * @param string $file
     * @param Request $request
     *
     * @return  BinaryFileResponse
     */
    public function get(string $file, Request $request): BinaryFileResponse
    {
        $current = Current::get($request);

        if (!$current->can('training_base.contracts.view') && !$current->can('training_base.contracts.modify')) {
            abort(403);
        }

        /** @var File $requestedFile */
        $requestedFile = File::query()->where('filename', $file)->firstOrFail();

        // check permission to view file
        if (!$current->position() && !$current->position()->hasRole(\App\Models\Permissions\Role::super)) {
            $count = TrainingBase::query()
                ->tap(new ForOrganization($current->organizationId()))
                ->whereHas('contracts', function (Builder $query) use ($requestedFile) {
                    $query->whereHas('files', function (Builder $query) use ($requestedFile) {
                        $query->where('id', $requestedFile->id);
                    });
                })
                ->count();
            if ($count === 0) {
                abort(403);
            }
        }

        return response()->file($requestedFile->path(), [
            'Cache-Control' => 'public',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-Length' => $requestedFile->size,
            'Content-Type' => $requestedFile->mime,
            'Content-Disposition' => "inline; filename=\"{$requestedFile->original_filename}\"",
        ]);
    }
}
