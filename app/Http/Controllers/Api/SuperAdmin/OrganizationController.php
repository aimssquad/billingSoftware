<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::query();
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        $organizations = $query->latest()->paginate(15);
        return OrganizationResource::collection($organizations);
    }

    public function show(Organization $organization)
    {
        $organization->load('activeSubscription.plan', 'settings','countryDetails');
        return new OrganizationResource($organization);
    }
}
