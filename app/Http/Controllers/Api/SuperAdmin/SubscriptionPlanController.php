<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = SubscriptionPlan::where('status', 'active')->get();
        return response()->json(['data' => $plans]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'plan_name'       => 'required|string|max:255|unique:subscription_plans,plan_name',
            'billing_cycle'   => 'required|in:monthly,yearly',
            'invoice_limit'   => 'required|integer|min:0',
            'price'           => 'required|numeric|min:0',
            'email_feature'   => 'nullable|boolean',
            'payment_feature' => 'nullable|boolean',
            'status'          => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $plan = SubscriptionPlan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Subscription plan created successfully',
            'data' => $plan
        ], 201);
    }

    /**
     * Show Single Plan
     */
    public function show($id): JsonResponse
    {
        $plan = SubscriptionPlan::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $plan
        ]);
    }

    /**
     * Update Plan
     */
    public function update(Request $request, $id): JsonResponse
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'plan_name'       => 'required|string|max:255|unique:subscription_plans,plan_name,' . $id,
            'billing_cycle'   => 'required|in:monthly,yearly',
            'invoice_limit'   => 'required|integer|min:0',
            'price'           => 'required|numeric|min:0',
            'email_feature'   => 'nullable|boolean',
            'payment_feature' => 'nullable|boolean',
            'status'          => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $plan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Subscription plan updated successfully',
            'data' => $plan
        ]);
    }

    /**
     * Delete Plan
     */
    public function destroy($id): JsonResponse
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscription plan deleted successfully'
        ]);
    }
}
