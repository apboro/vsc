<?php

namespace App;

use App\Models\Organization\Organization;
use App\Models\Permissions\Role;
use App\Models\Positions\Position;
use App\Models\User\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class Current
{
    protected const ORGANIZATION_COOKIE_NAME = 'vsc_organization';

    /** @var User This helper for. */
    protected User $user;

    /** @var Position|null Current user position. */
    protected ?Position $position = null;

    protected ?int $organizationIdOverride = null;

    /**
     * Factory.
     *
     * @param Request $request
     *
     * @return  Current
     */
    public static function get(Request $request): Current
    {
        return new Current($request->user(), $request);
    }

    /**
     * Create user current state.
     *
     * @param Request $request
     * @param User $user
     *
     * @return  void
     */
    public function __construct(User $user, Request $request)
    {
        $this->user = $user;
        $this->position = $user->position;

        // Handle organization override for admin users
        if ($user->position->hasRole(Role::super)) {
            if ($request->hasHeader('X-Vcs-Organization')) {
                $this->organizationIdOverride = $request->header('X-Vcs-Organization');
            } else if ($request->hasCookie(self::ORGANIZATION_COOKIE_NAME)) {
                $id = $request->cookie(self::ORGANIZATION_COOKIE_NAME);
                if (!empty($id) && $id !== 'null') {
                    $this->organizationIdOverride = $id;
                }
            }
        }
    }

    /**
     * Check current user permission.
     *
     * @param string|null $key
     * @param bool $fresh
     *
     * @return  bool
     */
    public function can(?string $key, bool $fresh = false): bool
    {
        return $this->position && $this->position->can($key, $fresh);
    }

    /**
     * Get current user position.
     *
     * @return  Position|null
     */
    public function position(): ?Position
    {
        return $this->position;
    }

    /**
     * Get current user position id.
     *
     * @return  int|null
     */
    public function positionId(): ?int
    {
        return $this->position->id ?? null;
    }

    /**
     * Get current user organization.
     *
     * @return  Organization|null
     */
    public function organization(): ?Organization
    {
        if ($this->position() && $this->position()->hasRole(Role::super)) {

            $organizationId = $this->organizationIdOverride;

            if (!$organizationId) {
                $organizationId = $this->position()->organization_id;
            }

            if ($organizationId) {
                /** @var Organization|null $organization */
                $organization = Organization::query()->where('id', $organizationId)->first();
                return $organization;
            }

            /** @var Organization|null $organization */
            $organization = Organization::query()->first();
            return $organization;
        }

        return $this->position()->organization ?? null;
    }

    /**
     * Get current user organization ID.
     *
     * @return  int|null
     */
    public function organizationId(): ?int
    {
        return $this->organization()->id ?? null;
    }

    /**
     * Make organization cookie.
     *
     * @param int|null $organizationId
     *
     * @return  Cookie
     */
    public function organizationToCookie(int $organizationId = null): Cookie
    {
        return cookie(self::ORGANIZATION_COOKIE_NAME, $organizationId ?? $this->organizationId());
    }

    /**
     * Get current username.
     *
     * @return  string|null
     */
    public function userName(): ?string
    {
        return isset($this->user) ? $this->user->profile->compactName : null;
    }
}
