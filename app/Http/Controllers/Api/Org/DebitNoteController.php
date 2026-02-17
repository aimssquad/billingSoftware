<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\DebitNote;
use Illuminate\Http\Request;

class DebitNoteController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        return response()->json(['data' => DebitNote::where('organization_id', $oid)->with('invoice')->latest()->paginate(15)]);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'debit_note_no' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
        ]);
        $v['organization_id'] = $request->attributes->get('organization_id');
        $m = DebitNote::create($v);
        return response()->json(['data' => $m], 201);
    }

    public function show(Request $request, $id)
    {
        $m = DebitNote::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        return response()->json(['data' => $m->load('invoice')]);
    }

    public function update(Request $request, $id)
    {
        $m = DebitNote::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $m->update($request->validate([
            'debit_note_no' => 'sometimes|string|max:50',
            'amount' => 'numeric|min:0',
            'reason' => 'nullable|string',
        ]));
        return response()->json(['data' => $m->fresh()]);
    }

    public function destroy(Request $request, $id)
    {
        $m = DebitNote::where('organization_id', $request->attributes->get('organization_id'))->findOrFail($id);
        $m->delete();
        return response()->json(null, 204);
    }
}
