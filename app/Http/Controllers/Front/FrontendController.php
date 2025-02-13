<?php

namespace App\Http\Controllers\Front;

use App\Current;
use App\Http\Controllers\Controller;
use App\Models\Permissions\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JsonException;

class FrontendController extends Controller
{
    /**
     * Handle requests to frontend index.
     *
     * @param Request $request
     *
     * @return  Response
     * @throws JsonException
     */
    public function index(Request $request): Response
    {
        $current = Current::get($request);

        return $this->adminPage($current);
    }

    /**
     * Render admin page.
     *
     * @param Current $current
     *
     * @return Response
     * @throws JsonException
     */
    protected function adminPage(Current $current): Response
    {
        return response()->view('admin', [
            'user' => json_encode([
                'name' => $this->e($current->position()->user->profile->compactName),
                'organization' => $this->e(__('common.root account caption')),
                'position' => $current->position()->title ? $this->e($current->position()->title->name) : null,
            ], JSON_THROW_ON_ERROR),
            'permissions' => json_encode(array_values($current->position() ? $current->position()->getPermissionsList() : []), JSON_THROW_ON_ERROR),
            'roles' => json_encode(
                array_values(
                    $current->position()->roles->map(function (Role $role) {
                        return $role->asString();
                    })
                        ->filter()
                        ->toArray()
                ), JSON_THROW_ON_ERROR
            ),
            'organization' => $current->organizationId(),
        ]);
    }

    /**
     * Prepare text to json encoding.
     *
     * @param string $text
     *
     * @return  string
     */
    protected function e(string $text): string
    {
        return str_replace('"', '\\"', $text);
    }
}
