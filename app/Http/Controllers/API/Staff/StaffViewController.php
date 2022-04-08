<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\PositionStatus;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffViewController extends ApiController
{
    public function view(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var User $user */
        if ($id === null ||
            null === ($user = User::query()
                ->with(['profile', 'position', 'position.status', 'position.info'])
                ->where('id', $id)
                ->first())
        ) {
            return APIResponse::notFound('Сотрудник не найен');
        }

        $values = [
            'full_name' => $user->profile->fullName,
            'created_at' => $user->created_at->format('d.m.Y'),
            'position_title' => $user->position->title->name ?? null,
            'position_title_id' => $user->position->title_id,
            'status' => $user->position->status->name,
            'status_id' => $user->position->status_id,
            'active' => $user->position->hasStatus(PositionStatus::active),

            'gender' => $user->profile->gender === 'male' ? 'мужской' : 'женский',
            'birth_date' => $user->profile->birthdate ? $user->profile->birthdate->format('d.m.Y') : null,

            'email' => $user->profile->email,
            'work_phone' => $user->position->info->work_phone,
            'work_phone_additional' => $user->position->info->work_phone_additional,
            'mobile_phone' => $user->profile->mobile_phone,

            'notes' => $user->position->info->notes,

            'has_access' => !empty($user->login) && !empty($user->password),
            'login' => $user->login,
        ];

        // send response
        return APIResponse::response($values);
    }
}
