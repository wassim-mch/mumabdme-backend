<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

        if (auth()->id() === $user->id) {
            return response()->json([
                'status' => 403,
                'message' => 'Tu ne peux pas supprimer ton propre compte.'
            ], 403);
        }

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
                'message' => 'Tu ne peux pas supprimer ton propre compte.'
            ], 403);
        }
        $user->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return response()->json([
            "status" => 200,
            "message" => "Profil mis à jour avec succès",
            "user" => $user
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => "Le mot de passe actuel est incorrect."
            ]);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            "status" => 200,
            "message" => "Mot de passe modifié avec succès",
        ], 200);
    }
}
