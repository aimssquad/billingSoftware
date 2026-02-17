<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\DeliveryChallan;
use Illuminate\Http\Request;

class DeliveryChallanController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        return response()->json(['data' => DeliveryChallan::where('organization_id', $oid)->with('customer')->latest()->paginate(15)]);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'challan_no' => 'required|string|max:50',
            'challan_date' => 'required|date',
            'status' => 'in:open,delivered',
        ]);
        $v['organization_id'] = $request->attributes->get('organization_id');
        $m = DeliveryChallan::create($v);
        return response()->json(['data' => $m], 201);
    }

    public function show(Request $request, $id)
    {
        $m = DeliveryChallan::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        return response()->json(['data' => $m->load('customer')]);
    }

    public function update(Request $request, $id)
    {
        $m = DeliveryChallan::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $m->update($request->validate([
            'challan_no' => 'sometimes|string|max:50',
            'challan_date' => 'date',
            'status' => 'in:open,delivered',
        ]));
        return response()->json(['data' => $m->fresh()]);
    }

    public function destroy(Request $request, $id)
    {
        $m = DeliveryChallan::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $m->delete();
        return response()->json(null, 204);
    }
}
