<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $validated['role_id'] = 2;
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            "status" => 201,
            "message" => "Compte créé avec succès.",
        ], 201);
    } 

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        // Try to authenticate
        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return response()->json([
                "status" => false,
                "message" => "Email ou mot de passe incorrect."
            ], 401);
        }

        $user = Auth::user();

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "status" => 200,
            "message" => "Connexion réussie.",
            "user" => new UserResource($user),
            "token" => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "status" => 200,
            "message" => "Déconnexion réussie."
        ], 200);
    }
}
