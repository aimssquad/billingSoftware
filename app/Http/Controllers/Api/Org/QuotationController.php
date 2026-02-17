<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $list = Quotation::where('organization_id', $oid)->with('customer')->latest()->paginate(15);
        return response()->json(['data' => $list]);
    }

    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quotation_no' => 'required|string|max:50',
            'quotation_date' => 'required|date',
            'valid_till' => 'nullable|date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,accepted,rejected',
        ]);
        $valid['organization_id'] = $oid;
        $model = Quotation::create($valid);
        return response()->json(['data' => $model], 201);
    }

    public function show(Request $request, Quotation $quotation)
    {
        if ($quotation->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        return response()->json(['data' => $quotation->load('customer')]);
    }

    public function update(Request $request, Quotation $quotation)
    {
        if ($quotation->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $valid = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'quotation_no' => 'sometimes|string|max:50',
            'quotation_date' => 'date',
            'valid_till' => 'nullable|date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,accepted,rejected',
        ]);
        $quotation->update($valid);
        return response()->json(['data' => $quotation->fresh()]);
    }

    public function destroy(Request $request, Quotation $quotation)
    {
        if ($quotation->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $quotation->delete();
        return response()->json(null, 204);
    }
}
