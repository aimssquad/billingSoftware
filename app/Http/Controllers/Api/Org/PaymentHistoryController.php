<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentHistoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Payments
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');

        $payments = Payment::where('organization_id', $oid)
            ->with('invoice')
            ->latest()
            ->paginate(15);

        return response()->json($payments);
    }

    /*
    |--------------------------------------------------------------------------
    | View Single Payment
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, $id)
    {
        $oid = $request->attributes->get('organization_id');

        $payment = Payment::where('organization_id', $oid)
            ->with('invoice')
            ->findOrFail($id);

        return response()->json(['data' => $payment]);
    }
}