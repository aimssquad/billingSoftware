<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\OrganizationInvoiceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationInvoiceSettingController extends Controller
{

    /**
     * Get Organization Invoice Setting
     */
    public function show(Request $request)
    {
        $oid = $request->attributes->get('organization_id');

        $setting = OrganizationInvoiceSetting::with('template')
                    ->where('organization_id',$oid)
                    ->first();

        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }

    /**
     * Create or Update Invoice Settings
     */
    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');

        $validator = Validator::make($request->all(), [
            'invoice_template_id' => 'required|exists:invoice_templates,id',
            'invoice_prefix'      => 'nullable|string',
            //'invoice_start_no'    => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'message'=>$validator->errors()->first()
            ],422);
        }

        $setting = OrganizationInvoiceSetting::updateOrCreate(

            ['organization_id'=>$oid],

            [
                'invoice_template_id'=>$request->invoice_template_id,
                'invoice_prefix'=>$request->invoice_prefix
            ]
        );

        return response()->json([
            'success'=>true,
            'message'=>'Invoice setting saved successfully',
            'data'=>$setting
        ]);
    }

    /**
     * Update Invoice Template Only
     */
    public function updateTemplate(Request $request)
    {
        $oid = $request->attributes->get('organization_id');

        $validator = Validator::make($request->all(), [
            'invoice_template_id'=>'required|exists:invoice_templates,id'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>$validator->errors()->first()
            ],422);
        }

        $setting = OrganizationInvoiceSetting::where('organization_id',$oid)->firstOrFail();

        $setting->update([
            'invoice_template_id'=>$request->invoice_template_id
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Invoice template updated successfully',
            'data'=>$setting->fresh()
        ]);
    }

}