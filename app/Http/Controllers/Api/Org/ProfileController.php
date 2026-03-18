<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Services\UsageTrackingService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Organization profile with all details needed for invoices and dashboard.
     * Includes: org details (letterhead / invoice header), settings, subscription, usage.
     */
    public function show(Request $request)
    {
        $organization = $request->attributes->get('organization');
        $organization->load('settings', 'activeSubscription.plan','countryDetails');

        $sub = $organization->activeSubscription;
        $usageSummary = app(UsageTrackingService::class)->getUsageSummary($organization->id);
        $invoiceLimit = $sub ? $sub->plan->invoice_limit : 0;
        $invoiceUsed = $usageSummary['invoice_count'] ?? 0;
        $orgDynamicField = \App\Models\CountryField::where('country', $organization->country)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'organization' => [
                'id' => $organization->id,
                'organization_code' => $organization->organization_code,
                'company_name' => $organization->company_name,
                'legal_name' => $organization->legal_name,
                'email' => $organization->email,
                'phone' => $organization->phone,
                'gstin' => $organization->gstin,
                'address' => $organization->address,
                'status' => $organization->status,
                'created_at' => $organization->created_at?->toIso8601String(),
                // Invoice / letterhead fields (same data, explicit for invoice templates)
                'invoice_display_name' => $organization->legal_name ?: $organization->company_name,
                'invoice_address' => $organization->address,
                'invoice_email' => $organization->email,
                'invoice_phone' => $organization->phone,
                'invoice_gstin' => $organization->gstin,
            ],
            'settings' => $organization->settings ? [
                'email_enabled' => $organization->settings->email_enabled,
                'payment_enabled' => $organization->settings->payment_enabled,
                'smtp_configured' => $organization->settings->smtp_configured,
                'payment_configured' => $organization->settings->payment_configured,
            ] : null,
            'subscription' => $sub ? [
                'id' => $sub->id,
                'status' => $sub->status,
                'start_date' => $sub->start_date->format('Y-m-d'),
                'end_date' => $sub->end_date->format('Y-m-d'),
                'plan' => [
                    'id' => $sub->plan->id,
                    'plan_name' => $sub->plan->plan_name,
                    'billing_cycle' => $sub->plan->billing_cycle,
                    'invoice_limit' => $sub->plan->invoice_limit,
                    'price' => (float) $sub->plan->price,
                ],
            ] : null,
            'usage' => [
                'usage_month' => $usageSummary['usage_month'] ?? now()->format('Y-m'),
                'invoice_count' => $invoiceUsed,
                'invoice_limit' => $invoiceLimit,
                'invoice_remaining' => max(0, $invoiceLimit - $invoiceUsed),
                'purchase_count' => $usageSummary['purchase_count'] ?? 0,
            ],

           'dynamic_field' => $orgDynamicField->map(function ($field) use ($organization) {

                // Find saved value
                $saved = $organization->countryDetails
                    ->where('field_key', $field->field_key)
                    ->first();

                return [
                    'id' => $field->id,
                    'country' => $field->country,
                    'field_key' => $field->field_key,
                    'field_label' => $field->field_label,
                    'field_type' => $field->field_type,
                    'is_required' => (bool) $field->is_required,
                    'is_active' => (bool) $field->is_active,

                    // ✅ IMPORTANT: attach saved value
                    'value' => $saved ? $saved->field_value : null,
                ];

            })->values(),
        ]);
    }

    /**
     * Update organization profile (invoice/letterhead details).
     */
    // public function update(Request $request)
    // {
    //     $organization = $request->attributes->get('organization');
    //     $valid = $request->validate([
    //         'company_name' => 'sometimes|string|max:255',
    //         'legal_name' => 'nullable|string|max:255',
    //         'email' => 'sometimes|email',
    //         'phone' => 'nullable|string|max:20',
    //         'gstin' => 'nullable|string|max:20',
    //         'address' => 'nullable|string',
    //     ]);
    //     $organization->update($valid);
    //     return response()->json([
    //         'message' => 'Profile updated.',
    //         'organization' => [
    //             'id' => $organization->id,
    //             'organization_code' => $organization->organization_code,
    //             'company_name' => $organization->company_name,
    //             'legal_name' => $organization->legal_name,
    //             'email' => $organization->email,
    //             'phone' => $organization->phone,
    //             'gstin' => $organization->gstin,
    //             'address' => $organization->address,
    //             'invoice_display_name' => $organization->legal_name ?: $organization->company_name,
    //             'invoice_address' => $organization->address,
    //             'invoice_email' => $organization->email,
    //             'invoice_phone' => $organization->phone,
    //             'invoice_gstin' => $organization->gstin,
    //         ],
    //     ]);
    // }

    public function update(Request $request)
    {
        $organization = $request->attributes->get('organization');

        // 1️⃣ Validate normal fields
        $valid = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'legal_name'   => 'nullable|string|max:255',
            'email'        => 'sometimes|email',
            'phone'        => 'nullable|string|max:20',
            'gstin'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
        ]);
        //dd('okk');
        $organization->update($valid);

        // 2️⃣ Fetch dynamic country fields
        $countryFields = \App\Models\CountryField::where('country', $organization->country)
            ->where('is_active', true)
            ->get();
        //dd($countryFields);
        $rules = [];

        foreach ($countryFields as $field) {
            if ($field->is_required) {
                $rules[$field->field_key] = $field->field_type === 'file'
                    ? 'required|file'
                    : 'required';
            }
        }

        $request->validate($rules);    

        foreach ($countryFields as $field) {

            $key = $field->field_key;

            // Skip if not sent in request
            if (!$request->has($key) && !$request->hasFile($key)) {
                continue;
            }

            $value = null;

            // 3️⃣ Handle file type
            if ($field->field_type === 'file' && $request->hasFile($key)) {

                $file = $request->file($key);
                $fileName = time().'_'.$key.'.'.$file->getClientOriginalExtension();

                $path = $file->storeAs('organization/'.$organization->id, $fileName, 'public');

                $value = $path;

            } else {
                $value = $request->$key;
            }

            // 4️⃣ Update or Create
            $organization->countryDetails()->updateOrCreate(
                ['field_key' => $key],
                ['field_value' => $value]
            );
        }

        return response()->json([
            'message' => 'Profile updated successfully.',
            'organization' => new \App\Http\Resources\OrganizationResource(
                $organization->load('countryDetails', 'activeSubscription.plan', 'settings')
            ),
        ]);
    }



}
