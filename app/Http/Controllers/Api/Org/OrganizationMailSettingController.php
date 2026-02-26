<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMailSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrganizationMailSettingController extends Controller
{
    /**
     * Show current SMTP settings
     */
    public function show(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');

        $setting = OrganizationMailSetting::where('organization_id', $organizationId)
            ->first();

        return response()->json([
            'data' => $setting,
            'message' => $setting ? 'Mail settings retrieved successfully' : 'No mail settings found for this organization',
        ]);
    }

    /**
     * Create or Update SMTP settings
     */
    public function storeOrUpdate(Request $request): JsonResponse
    {
        $organizationId = $request->attributes->get('organization_id');

        $valid = $request->validate([
            'driver' => 'required|string|in:smtp',
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
            'encryption' => 'nullable|in:tls,ssl',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $valid['organization_id'] = $organizationId;

        $setting = OrganizationMailSetting::updateOrCreate(
            ['organization_id' => $organizationId],
            $valid
        );

        return response()->json([
            'message' => 'Mail settings saved successfully',
            'data' => $setting
        ]);
    }

    /**
     * Delete SMTP settings
     */
    public function destroy(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');

        OrganizationMailSetting::where('organization_id', $organizationId)
            ->delete();

        return response()->json([
            'message' => 'Mail settings deleted successfully'
        ]);
    }
}