<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
 
    public function index()
    {
        $countries = Country::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

   
   public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:countries,name',
            'slug' => 'nullable|string|unique:countries,slug',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The name has already been taken.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $country = Country::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Country created successfully',
            'data' => $country
        ]);
    }

 
    public function show($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Country not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $country
        ]);
    }

  
    public function update(Request $request, $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Country not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:countries,name,' . $id,
            'slug' => 'nullable|string|unique:countries,slug,' . $id,
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The name has already been taken',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $country->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Country updated successfully',
            'data' => $country
        ]);
    }

  
    public function destroy($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Country not found'
            ], 404);
        }

        $country->delete();

        return response()->json([
            'success' => true,
            'message' => 'Country deleted successfully'
        ]);
    }

  
    public function active()
    {
        $countries = Country::where('is_active', true)->select(['name','slug'])->get();

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }
}