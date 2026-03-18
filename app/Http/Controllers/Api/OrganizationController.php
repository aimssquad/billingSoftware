<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use App\Mail\RegistrationSuccessfulMail;
use App\Models\Organization;
use App\Models\OrganizationSetting;
use App\Models\Role;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Public registration: create organization + first user (org_owner) + default subscription + settings.
     */
    public function register(Request $request): JsonResponse
    {
        $valid = $request->validate([
            'company_name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|string|min:8',
            'owner_phone' => 'nullable|string|max:20',
            //'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail(1);
        if ($plan->status !== 'active') {
            return response()->json(['message' => 'Selected plan is not active.'], 422);
        }

        $orgCode = strtoupper(Str::random(8));
        while (Organization::where('organization_code', $orgCode)->exists()) {
            $orgCode = strtoupper(Str::random(8));
        }

        $organization = DB::transaction(function () use ($valid, $orgCode, $plan) {
            $organization = Organization::create([
                'organization_code' => $orgCode,
                'company_name' => $valid['company_name'],
                'legal_name' => $valid['legal_name'] ?? null,
                'email' => $valid['email'],
                'phone' => $valid['phone'] ?? null,
                'gstin' => $valid['gstin'] ?? null,
                'address' => $valid['address'] ?? null,
                'country' => $valid['country'] ?? null,
                'status' => 'active',
            ]);

            $orgOwnerRoleId = Role::where('slug', 'org_owner')->value('id');
            $user = User::create([
                'organization_id' => $organization->id,
                'role_id' => $orgOwnerRoleId,
                'name' => $valid['owner_name'],
                'email' => $valid['owner_email'],
                'phone' => $valid['owner_phone'] ?? null,
                'password' => $valid['owner_password'],
                'status' => 'active',
            ]);

            $subscriptionService = app(SubscriptionService::class);
            $subscriptionService->createSubscription($organization, $plan);

            OrganizationSetting::create([
                'organization_id' => $organization->id,
                'email_enabled' => $plan->email_feature,
                'payment_enabled' => $plan->payment_feature,
                'smtp_configured' => false,
                'payment_configured' => false,
            ]);

            return $organization;
        });

        $user = $organization->users()->whereHas('role', fn ($q) => $q->where('slug', 'org_owner'))->first();
        $token = $user->createToken('api')->plainTextToken;

        $siteUrl = rtrim(config('app.frontend_url', config('app.url')), '/');
        try {
            Mail::to($organization->email)->send(new RegistrationSuccessfulMail(
                $organization,
                $user,
                $valid['owner_password'],
                $siteUrl
            ));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'message' => 'Organization registered successfully.',
            'organization' => new OrganizationResource($organization),
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
