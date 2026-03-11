<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceTemplate;
use App\Models\OrganizationSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceTemplateController extends Controller
{

    /**
     * List Templates
     */
    public function index(): JsonResponse
    {
        $templates = InvoiceTemplate::where('status', true)->get();

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    public function orgIndex(Request $request): JsonResponse
    {
        $templates = InvoiceTemplate::where('status', true)->get();

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|unique:invoice_templates,name',
            'preview_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'status'        => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $request->all();

        // generate slug automatically
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = $request->file('preview_image')
                ->store('invoice_templates', 'public');
        }

        $template = InvoiceTemplate::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Invoice template created successfully',
            'data' => $template
        ], 201);
    }

    /**
     * Show Single Template
     */
    public function show($id): JsonResponse
    {
        $template = InvoiceTemplate::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $template
        ]);
    }

  
    public function update(Request $request, $id): JsonResponse
    {
        $template = InvoiceTemplate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|unique:invoice_templates,name,' . $id,
            'preview_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'status'        => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $request->all();

        // regenerate slug when name changes
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('preview_image')) {

            if ($template->preview_image && Storage::disk('public')->exists($template->preview_image)) {
                Storage::disk('public')->delete($template->preview_image);
            }

            $data['preview_image'] = $request->file('preview_image')
                ->store('invoice_templates', 'public');
        }

        $template->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Invoice template updated successfully',
            'data' => $template->fresh()
        ]);
    }

    /**
     * Delete Template
     */
    public function destroy($id): JsonResponse
    {
        $template = InvoiceTemplate::findOrFail($id);

        if ($template->preview_image && Storage::disk('public')->exists($template->preview_image)) {
            Storage::disk('public')->delete($template->preview_image);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invoice template deleted successfully'
        ]);
    }
}