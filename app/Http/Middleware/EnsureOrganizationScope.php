<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationScope
{
    /**
     * Ensure user belongs to an organization (org_owner or org_user) and set current organization on request.
     * Super admin must send X-Organization-Id header to act on behalf of an org.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            $orgId = $request->header('X-Organization-Id');
            if (! $orgId) {
                return response()->json([
                    'message' => 'Super admin must send X-Organization-Id header for org-scoped routes.',
                ], 400);
            }
            $organization = Organization::find($orgId);
            if (! $organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }
        } else {
            if (! $user->organization_id) {
                return response()->json(['message' => 'User has no organization.'], 403);
            }
            $organization = $user->organization;
            if (! $organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }
        }

        $request->attributes->set('organization', $organization);
        $request->attributes->set('organization_id', $organization->id);
        app()->instance('current_organization_id', $organization->id);

        return $next($request);
    }
}
