<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    // public function show(Request $request)
    // {
    //     $user = $request->user();
    //     $organization = $request->attributes->get('organization');
    //     return response()->json([
    //         'user' => new UserResource($user),
    //         'organization' => new OrganizationResource($organization),
    //     ]);
    // }

    public function show(Request $request)
    {
        $user = $request->user();
        $organization = $request->attributes->get('organization');

        $organization->load(
            'countryDetails',
            'activeSubscription.plan',
            'settings'
        );

        return response()->json([
            'user' => new UserResource($user),
            'organization' => new OrganizationResource($organization),
        ]);
    }
}
