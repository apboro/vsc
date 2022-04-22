<?php

namespace App\Http\Controllers\API\TrainingBase;

use App\Http\Controllers\ApiController;
use App\Models\Common\File;
use App\Models\User\User;
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
        /** @var User $user */
        $user = $request->user();

        if (!$user->position->can('training_base.contracts.view') && !$user->position->can('training_base.contracts.modify')) {
            return abort(403);
        }

        /** @var File $document */
        $document = File::query()->where('filename', $file)->firstOrFail();

        return response()->file($document->path(), [
            'Cache-Control' => 'public',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-Length' => $document->size,
            'Content-Type' => $document->mime,
            'Content-Disposition' => "inline; filename=\"{$document->original_filename}\"",
        ]);
    }
}
