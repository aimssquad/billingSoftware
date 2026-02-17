<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');
        $customers = Customer::where('organization_id', $organizationId)->latest()->paginate(15);
        return response()->json(['data' => $customers]);
    }

    public function store(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:20',
            'billing_address' => 'nullable|string',
        ]);
        $valid['organization_id'] = $organizationId;
        $valid['status'] = 'active';
        $customer = Customer::create($valid);
        return response()->json(['data' => $customer], 201);
    }

    public function show(Request $request, Customer $customer)
    {
        if ($customer->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        return response()->json(['data' => $customer]);
    }

    public function update(Request $request, Customer $customer)
    {
        if ($customer->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $valid = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:20',
            'billing_address' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive',
        ]);
        $customer->update($valid);
        return response()->json(['data' => $customer->fresh()]);
    }

    public function destroy(Request $request, Customer $customer)
    {
        if ($customer->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $customer->delete();
        return response()->json(null, 204);
    }
}
