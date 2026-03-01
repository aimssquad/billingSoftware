<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $list = Vendor::where('organization_id', $oid)->latest()->paginate(15);
        return response()->json(['data' => $list]);
    }

    public function allVendors(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $list = Vendor::where('organization_id', $oid)->where('status', 'active')->get();
        return response()->json(['data' => $list]);
    }

    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $valid['organization_id'] = $oid;
        $valid['status'] = 'active';
        $model = Vendor::create($valid);
        return response()->json(['data' => $model], 201);
    }

    public function show(Request $request, Vendor $vendor)
    {
        if ($vendor->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        return response()->json(['data' => $vendor]);
    }

    public function update(Request $request, Vendor $vendor)
    {
        if ($vendor->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $valid = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive',
        ]);
        $vendor->update($valid);
        return response()->json(['data' => $vendor->fresh()]);
    }

    public function destroy(Request $request, Vendor $vendor)
    {
        if ($vendor->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $vendor->delete();
        return response()->json(null, 204);
    }
}
