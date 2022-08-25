<?php

namespace App\Http\Controllers\Leads\Helpers;

use App\Http\Middleware\LeadsProtect;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LeadSession
{
    /**
     * Get parameters from request.
     *
     * @param Request $request
     *
     * @return  array
     */
    public static function getKey(Request $request): array
    {
        if ($request->hasHeader('X-Vsc-Key')) {
            $key = Crypt::decrypt($request->header('X-Vsc-Key'));
        }

        return [
            'organization_id' => $key ?? null,
        ];
    }

    /**
     * Make encrypted session.
     *
     * @param int $organizationId
     * @param string $ip
     *
     * @return  string
     */
    public static function makeSession(int $organizationId, string $ip): string
    {
        $session = [
            'organization_id' => $organizationId,
            'ip' => $ip,
        ];

        return Crypt::encrypt($session);
    }

    /**
     * Get organization ID.
     *
     * @param Request $request
     *
     * @return  int|null
     */
    public static function getOrganizationId(Request $request): ?int
    {
        if ($request->hasHeader(LeadsProtect::HEADER_NAME)) {
            try {
                $session = Crypt::decrypt($request->header(LeadsProtect::HEADER_NAME));
            } catch (Exception $exception) {
                return null;
            }
        }

        return $session['organization_id'] ?? null;
    }
}
