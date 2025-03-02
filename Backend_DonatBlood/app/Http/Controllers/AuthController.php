<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|unique:users',
            'role' => 'required|in:donor,recipient,admin',
            'blood_type' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'blood_type' => $request->blood_type,
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }


    /**
     * Login a user and return token
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required', // Email or phone
            'password' => 'required',
            'remember' => 'boolean', // Optional "Remember Me"
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['login' => 'Invalid credentials']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if ($request->remember) {
            $user->update(['remember_token' => Str::random(60)]);
        } else {
            $user->update(['remember_token' => null]);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'remember_token' => $user->remember_token,
            'user' => $user,
        ]);
    }




    /**
     * Logout the user and revoke tokens
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Delete all tokens
        $user->remember_token = null; // Remove remember token
        $user->save();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
