<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankAccountController extends Controller
{

    public function index(Request $request)
    {
        $oid = $request->attributes->get('organization_id');

        $list = BankAccount::where('organization_id',$oid)
                ->latest()
                ->paginate(15);

        return response()->json(['data'=>$list]);
    }

    public function store(Request $request)
    {
        $oid = $request->attributes->get('organization_id');

        $valid = $request->validate([

            'account_name'=>'nullable|string|max:100',
            'bank_name'=>'required|string|max:255',
            'account_holder_name'=>'required|string|max:255',
            'account_number'=>'required|string|max:100',

            'iban'=>'nullable|string|max:100',
            'swift_code'=>'nullable|string|max:100',
            'routing_number'=>'nullable|string|max:100',
            'ifsc_code'=>'nullable|string|max:50',
            'sort_code'=>'nullable|string|max:50',

            'branch_name'=>'nullable|string|max:255',
            'branch_address'=>'nullable|string',

            'bank_country'=>'nullable|string|max:100',
            'currency'=>'nullable|string|max:10',

            'upi_id'=>'nullable|string|max:100',
            'qr_code'=>'nullable|image|mimes:png,jpg,jpeg|max:2048',

            'is_default'=>'nullable|boolean',
            'status'=>'nullable|in:active,inactive'
        ]);

        $valid['organization_id']=$oid;

        if($request->hasFile('qr_code'))
        {
            $valid['qr_code']=$request->file('qr_code')->store('qr_codes','public');
        }

        if(!empty($valid['is_default']))
        {
            BankAccount::where('organization_id',$oid)->update(['is_default'=>false]);
        }

        $bank = BankAccount::create($valid);

        return response()->json(['data'=>$bank],201);
    }

    public function show(Request $request,$id)
    {
        $bank = BankAccount::where('organization_id',$request->attributes->get('organization_id'))
                ->findOrFail($id);

        return response()->json(['data'=>$bank]);
    }

    public function update(Request $request,$id)
    {  
        $oid = $request->attributes->get('organization_id');

        $bank = BankAccount::where('organization_id',$oid)->findOrFail($id);

        $valid = $request->validate([

            'account_name'=>'sometimes|string|max:100',
            'bank_name'=>'sometimes|string|max:255',
            'account_holder_name'=>'sometimes|string|max:255',
            'account_number'=>'sometimes|string|max:100',

            'iban'=>'nullable|string|max:100',
            'swift_code'=>'nullable|string|max:100',
            'routing_number'=>'nullable|string|max:100',
            'ifsc_code'=>'nullable|string|max:50',
            'sort_code'=>'nullable|string|max:50',

            'branch_name'=>'nullable|string|max:255',
            'branch_address'=>'nullable|string',

            'bank_country'=>'nullable|string|max:100',
            'currency'=>'nullable|string|max:10',

            'upi_id'=>'nullable|string|max:100',
            'qr_code'=>'nullable|image|mimes:png,jpg,jpeg|max:2048',

            'is_default'=>'nullable|boolean',
            'status'=>'nullable|in:active,inactive'
        ]);
    
        if($request->hasFile('qr_code'))
        {
            if($bank->qr_code && Storage::disk('public')->exists($bank->qr_code))
            {
                Storage::disk('public')->delete($bank->qr_code);
            }

            $valid['qr_code']=$request->file('qr_code')->store('qr_codes','public');
        }

        if(!empty($valid['is_default']))
        {
            BankAccount::where('organization_id',$oid)->update(['is_default'=>false]);
        }
        
        $bank->update($valid);

        return response()->json(['data'=>$bank->fresh()]);
    }

    public function makePrimary(Request $request, $id)
    {
        $oid = $request->attributes->get('organization_id');

        $request->validate([
            'account_name' => 'nullable|string|max:100'
        ]);

        $bank = BankAccount::where('organization_id', $oid)->findOrFail($id);

        // set other banks to false
        BankAccount::where('organization_id', $oid)
            ->where('id', '!=', $id)
            ->update(['is_default' => false]);

        // set selected bank as primary
        $bank->update([
            'is_default' => true,
            'account_name' => $request->account_name ?? $bank->account_name
        ]);

        return response()->json([
            'message' => 'Primary bank account updated successfully',
            'data' => $bank->fresh()
        ]);
    }

    public function destroy(Request $request,$id)
    {
        $bank = BankAccount::where('organization_id',$request->attributes->get('organization_id'))
                ->findOrFail($id);

        if($bank->qr_code && Storage::disk('public')->exists($bank->qr_code))
        {
            Storage::disk('public')->delete($bank->qr_code);
        }

        $bank->delete();

        return response()->json(null,204);
    }
}