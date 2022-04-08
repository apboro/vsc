<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Positions\Position;
use App\Models\User\User;
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
        /** @var User $user */
        $user = $request->user();

        return $this->adminPage($user->position);
    }

    /**
     * Render admin page.
     *
     * @param Position $position
     *
     * @return Response
     * @throws JsonException
     */
    protected function adminPage(Position $position): Response
    {
        return response()->view('admin', [
            'user' => json_encode([
                'name' => $this->e($position->user->profile->compactName),
                'organization' => $this->e(__('common.root account caption')),
                'position' => $position->title ? $this->e($position->title->name) : null,
            ], JSON_THROW_ON_ERROR),
            'permissions' => json_encode(array_values($position->getPermissionsList()), JSON_THROW_ON_ERROR),
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
