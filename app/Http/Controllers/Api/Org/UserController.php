<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');
        $users = User::where('organization_id', $organizationId)->latest()->paginate(15);
        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);
        $valid['organization_id'] = $organizationId;
        $valid['password'] = bcrypt($valid['password']);
        $valid['status'] = 'active';
        $user = User::create($valid);
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function show(Request $request, User $user)
    {
        $this->ensureSameOrg($request, $user);
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $this->ensureSameOrg($request, $user);
        $valid = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'status' => 'sometimes|in:active,inactive,suspended',
        ]);
        if (! empty($valid['password'])) {
            $valid['password'] = bcrypt($valid['password']);
        } else {
            unset($valid['password']);
        }
        $user->update($valid);
        return new UserResource($user->fresh());
    }

    public function destroy(Request $request, User $user)
    {
        $this->ensureSameOrg($request, $user);
        $user->delete();
        return response()->json(null, 204);
    }

    private function ensureSameOrg(Request $request, User $user): void
    {
        if ($user->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
    }
}
