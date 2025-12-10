<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 200,
            'users' => $users
        ], 200);
    }

    public function show(User $user)
    {
        return response()->json([
            'status' => 200,
            'user' => $user
        ], 200);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'sometimes|integer|exists:roles,id',
        ]);

        if ($request->has('role_id')) {
            $user->role_id = $request->role_id;
        }

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return response()->json([
                'status' => 403,
                'message' => 'You cannot delete yourself'
            ], 403);
        }
        $user->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully'
        ], 200);
    }
}
