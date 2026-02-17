<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * List roles that can be assigned in this organization (org_owner, org_user only).
     */
    public function index(Request $request)
    {
        $roles = Role::whereIn('slug', ['org_owner', 'org_user'])->get(['id', 'name', 'slug']);
        return response()->json(['data' => $roles]);
    }
}
