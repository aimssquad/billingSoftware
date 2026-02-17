<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\VendorCreditNote;
use Illuminate\Http\Request;

class VendorCreditNoteController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $list = VendorCreditNote::where('organization_id', $oid)->with('vendor')->latest()->paginate(15);
        return response()->json(['data' => $list]);
    }

    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'credit_note_no' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
        ]);
        $valid['organization_id'] = $oid;
        $model = VendorCreditNote::create($valid);
        return response()->json(['data' => $model], 201);
    }

    public function show(Request $request, VendorCreditNote $vendor_credit_note)
    {
        if ($vendor_credit_note->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        return response()->json(['data' => $vendor_credit_note->load('vendor')]);
    }

    public function update(Request $request, VendorCreditNote $vendor_credit_note)
    {
        if ($vendor_credit_note->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $valid = $request->validate([
            'credit_note_no' => 'sometimes|string|max:50',
            'amount' => 'numeric|min:0',
            'reason' => 'nullable|string',
        ]);
        $vendor_credit_note->update($valid);
        return response()->json(['data' => $vendor_credit_note->fresh()]);
    }

    public function destroy(Request $request, VendorCreditNote $vendor_credit_note)
    {
        if ($vendor_credit_note->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $vendor_credit_note->delete();
        return response()->json(null, 204);
    }
}
