<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CountryField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryFieldController extends Controller
{
    /**
     * List all country fields
     */
    public function index()
    {
        $data = CountryField::orderBy('country')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Country fields list fetched successfully',
            'data' => $data
        ]);
    }

    /**
     * Store new country field
     */
    public function store(Request $request)
    {
        $valid = $request->validate([
            'country'      => 'required|string|max:100',
            'field_key'    => 'required|string|max:100',
            'field_label'  => 'required|string|max:255',
            'field_type'   => 'required|in:text,number,file,date',
            'is_required'  => 'nullable|boolean',
            'is_active'    => 'nullable|boolean',
        ]);

        // Make key safe format
        $valid['field_key'] = Str::slug($valid['field_key'], '_');

        // Prevent duplicate per country
        $exists = CountryField::where('country', strtolower($valid['country']))
            ->where('field_key', $valid['field_key'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Field already exists for this country'
            ], 422);
        }

        $valid['country'] = strtolower($valid['country']);

        $data = CountryField::create($valid);

        return response()->json([
            'success' => true,
            'message' => 'Country field created successfully',
            'data' => $data
        ], 201);
    }

    /**
     * Update country field
     */
    public function update(Request $request, $id)
    {
        $field = CountryField::findOrFail($id);

        $valid = $request->validate([
            'field_label'  => 'nullable|string|max:255',
            'field_type'   => 'nullable|in:text,number,file,date',
            'is_required'  => 'nullable|boolean',
            'is_active'    => 'nullable|boolean',
        ]);

        $field->update($valid);

        return response()->json([
            'success' => true,
            'message' => 'Country field updated successfully',
            'data' => $field
        ]);
    }

    /**
     * Delete country field
     */
    public function destroy($id)
    {
        $field = CountryField::findOrFail($id);
        $field->delete();

        return response()->json([
            'success' => true,
            'message' => 'Country field deleted successfully'
        ]);
    }

    /**
     * Get fields by country (For registration form dynamic load)
     */
    public function getByCountry($country)
    {
        $data = CountryField::where('country', strtolower($country))
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function country()
    {
        $templates = CountryField::where('status', true)->select('country')->get();

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }
}
