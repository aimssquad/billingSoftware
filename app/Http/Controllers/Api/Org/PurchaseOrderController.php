<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $list = PurchaseOrder::where('organization_id', $oid)->with('vendor')->latest()->paginate(15);
        return response()->json(['data' => $list]);
    }

    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'po_no' => 'required|string|max:50',
            'po_date' => 'required|date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,received',
        ]);
        $valid['organization_id'] = $oid;
        $model = PurchaseOrder::create($valid);
        return response()->json(['data' => $model], 201);
    }

    public function show(Request $request, $id)
    {
        $po = PurchaseOrder::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        return response()->json(['data' => $po->load('vendor')]);
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $valid = $request->validate([
            'vendor_id' => 'sometimes|exists:vendors,id',
            'po_no' => 'sometimes|string|max:50',
            'po_date' => 'date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,received',
        ]);
        $po->update($valid);
        return response()->json(['data' => $po->fresh()]);
    }

    public function destroy(Request $request, $id)
    {
        $po = PurchaseOrder::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $po->delete();
        return response()->json(null, 204);
    }
}
