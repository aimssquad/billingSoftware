<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\PurchaseBill;
use Illuminate\Http\Request;

class PurchaseBillController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $list = PurchaseBill::where('organization_id', $oid)->with('vendor')->latest()->paginate(15);
        return response()->json(['data' => $list]);
    }

    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'bill_no' => 'required|string|max:50',
            'bill_date' => 'required|date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:unpaid,paid',
        ]);
        $valid['organization_id'] = $oid;
        $model = PurchaseBill::create($valid);
        return response()->json(['data' => $model], 201);
    }

    public function show(Request $request, $id)
    {
        $bill = PurchaseBill::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        return response()->json(['data' => $bill->load('vendor')]);
    }

    public function update(Request $request, $id)
    {
        $bill = PurchaseBill::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $valid = $request->validate([
            'vendor_id' => 'sometimes|exists:vendors,id',
            'bill_no' => 'sometimes|string|max:50',
            'bill_date' => 'date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:unpaid,paid',
        ]);
        $bill->update($valid);
        return response()->json(['data' => $bill->fresh()]);
    }

    public function destroy(Request $request, $id)
    {
        $bill = PurchaseBill::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $bill->delete();
        return response()->json(null, 204);
    }
}
