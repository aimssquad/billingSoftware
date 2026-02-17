<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\ProformaInvoice;
use Illuminate\Http\Request;

class ProformaInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $list = ProformaInvoice::where('organization_id', $oid)->with('customer')->latest()->paginate(15);
        return response()->json(['data' => $list]);
    }

    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'pi_no' => 'required|string|max:50',
            'pi_date' => 'required|date',
            'valid_till' => 'nullable|date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,converted',
        ]);
        $valid['organization_id'] = $oid;
        $model = ProformaInvoice::create($valid);
        return response()->json(['data' => $model], 201);
    }

    public function show(Request $request, ProformaInvoice $proforma_invoice)
    {
        if ($proforma_invoice->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        return response()->json(['data' => $proforma_invoice->load('customer')]);
    }

    public function update(Request $request, ProformaInvoice $proforma_invoice)
    {
        if ($proforma_invoice->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $valid = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'pi_no' => 'sometimes|string|max:50',
            'pi_date' => 'date',
            'valid_till' => 'nullable|date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,converted',
        ]);
        $proforma_invoice->update($valid);
        return response()->json(['data' => $proforma_invoice->fresh()]);
    }

    public function destroy(Request $request, ProformaInvoice $proforma_invoice)
    {
        if ($proforma_invoice->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
        $proforma_invoice->delete();
        return response()->json(null, 204);
    }
}
