<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use Illuminate\Http\Request;

class CreditNoteController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $list = CreditNote::where('organization_id', $oid)->with('invoice')->latest()->paginate(15);
        return response()->json(['data' => $list]);
    }

    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'credit_note_no' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
        ]);
        $valid['organization_id'] = $oid;
        $model = CreditNote::create($valid);
        return response()->json(['data' => $model], 201);
    }

    public function show(Request $request, $id)
    {
        $credit_note = CreditNote::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        return response()->json(['data' => $credit_note->load('invoice')]);
    }

    public function update(Request $request, $id)
    {
        $credit_note = CreditNote::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $valid = $request->validate([
            'credit_note_no' => 'sometimes|string|max:50',
            'amount' => 'numeric|min:0',
            'reason' => 'nullable|string',
        ]);
        $credit_note->update($valid);
        return response()->json(['data' => $credit_note->fresh()]);
    }

    public function destroy(Request $request, $id)
    {
        $credit_note = CreditNote::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $credit_note->delete();
        return response()->json(null, 204);
    }
}
