<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user (donor or hospital)
     */
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:donor,hospital',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Ensure unique email
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the user (authentication part)
        $user = User::create([
            'role' => $request->role,
            'email' => $request->email,
            'phone' => $request->phone, // Optional phone for user
            'password' => Hash::make($request->password),
        ]);

        // Register specific role-related data (donor or hospital)
        if ($request->role == 'donor') {
            return $this->registerDonor($user, $request);
        } elseif ($request->role == 'hospital') {
            return $this->registerHospital($user, $request);
        }
    }

    // Handle donor registration
    private function registerDonor(User $user, Request $request)
    {
        $donorValidator = Validator::make($request->all(), [
            'cin' => 'required|string|unique:donors,cin',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        if ($donorValidator->fails()) {
            return response()->json(['errors' => $donorValidator->errors()], 422);
        }

        // Create the donor record and copy email and phone from the user
        $donor = Donor::create([
            'user_id' => $user->id,
            'cin' => $request->cin,
            'blood_type' => $request->blood_type,
            'email' => $user->email,  // Copy email from user
            'phone' => $user->phone,  // Copy phone from user
            'availability' => true, // Default to available
        ]);

        return response()->json(['message' => 'Donor registered successfully', 'user' => $user, 'donor' => $donor], 201);
    }


    // Handle hospital registration
    private function registerHospital(User $user, Request $request)
    {
        $hospitalValidator = Validator::make($request->all(), [
            'location' => 'required|string|max:255',
        ]);

        if ($hospitalValidator->fails()) {
            return response()->json(['errors' => $hospitalValidator->errors()], 422);
        }

        $hospital = Hospital::create([
            'user_id' => $user->id,
            'location' => $request->location,
            'name' => $request->name, // Hospital name
        ]);

        return response()->json(['message' => 'Hospital registered successfully', 'user' => $user, 'hospital' => $hospital], 201);
    }

    /**
     * Login user and return token
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)->first();

        if (!$user) {
            if (is_numeric($request->login)) {
                $donor = Donor::where('phone', $request->login)->first();
                $hospital = Hospital::where('phone', $request->login)->first();

                if ($donor) {
                    $user = $donor->user;
                } elseif ($hospital) {
                    $user = $hospital->user;
                }
            }
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['login' => 'Invalid credentials']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Logout user and revoke current token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
