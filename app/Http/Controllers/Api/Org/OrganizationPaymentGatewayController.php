<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganizationPaymentGateway;

class OrganizationPaymentGatewayController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List All Gateways
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');
        //dd($oid);
        $gateways = OrganizationPaymentGateway::where('organization_id', $oid)
            ->get();

        return response()->json(['data' => $gateways]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store or Update Gateway
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'gateway' => 'required|in:stripe,razorpay,paypal',
            'public_key' => 'required|string',
            'secret_key' => 'required|string',
            'webhook_secret' => 'nullable|string',
            'mode' => 'required|in:sandbox,live'
        ]);

        $oid = $request->attributes->get('organization_id');
        //dd($oid);
        $gateway = OrganizationPaymentGateway::updateOrCreate(
            [
                'organization_id' => $oid,
                'gateway' => $request->gateway
            ],
            [
                'public_key' => $request->public_key,
                'secret_key' => $request->secret_key,
                'webhook_secret' => $request->webhook_secret,
                'mode' => $request->mode,
                'is_active' => false
            ]
        );

        return response()->json([
            'message' => 'Gateway saved successfully',
            'data' => $gateway
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Activate Gateway (Only One Active)
    |--------------------------------------------------------------------------
    */

    public function activate(Request $request, $id)
    {
        $oid = $request->attributes->get('organization_id');

        $gateway = OrganizationPaymentGateway::where('organization_id', $oid)
            ->where('id', $id)
            ->firstOrFail();

        // Deactivate all first
        OrganizationPaymentGateway::where('organization_id', $oid)
            ->update(['is_active' => false]);

        $gateway->update(['is_active' => true]);

        return response()->json([
            'message' => 'Gateway activated successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Gateway
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request, $id)
    {
        $oid = $request->attributes->get('organization_id');

        $gateway = OrganizationPaymentGateway::where('organization_id', $oid)
            ->where('id', $id)
            ->firstOrFail();

        $gateway->delete();

        return response()->json([
            'message' => 'Gateway deleted successfully'
        ]);
    }

}    