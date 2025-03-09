<?php

// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user (donor or hospital)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:donor,hospital', // Check for either donor or hospital
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Register donor
        if ($request->role == 'donor') {
            return $this->registerDonor($request);
        }

        // Register hospital
        if ($request->role == 'hospital') {
            return $this->registerHospital($request);
        }
    }

    // Handle donor registration
    private function registerDonor(Request $request)
    {
        $donorValidator = Validator::make($request->all(), [
            'cin' => 'required|string|unique:donors',
            'phone' => 'nullable|string|unique:donors',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'required|string',
        ]);

        if ($donorValidator->fails()) {
            return response()->json(['errors' => $donorValidator->errors()], 422);
        }

        // Create User
        $user = User::create([
            'role' => 'donor', 
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create Donor Profile
        $donor = Donor::create([
            'user_id' => $user->id,
            'cin' => $request->cin,
            'name' => $request->name,
            'phone' => $request->phone,
            'blood_type' => $request->blood_type,
            'address' => $request->address,
            'availability' => true, 
        ]);

        return response()->json(['message' => 'Donor registered successfully', 'user' => $user, 'donor' => $donor], 201);
    }

    // Handle hospital registration
    private function registerHospital(Request $request)
    {
        $hospitalValidator = Validator::make($request->all(), [
            'hospital_name' => 'required|string',
            'location' => 'required|string',
            'contact_number' => 'nullable|string|unique:hospitals',
        ]);

        if ($hospitalValidator->fails()) {
            return response()->json(['errors' => $hospitalValidator->errors()], 422);
        }

        // Create User
        $user = User::create([
            'role' => 'hospital',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create Hospital Profile
        $hospital = Hospital::create([
            'user_id' => $user->id,
            'hospital_name' => $request->hospital_name,
            'location' => $request->location,
            'contact_number' => $request->contact_number,
        ]);

        return response()->json(['message' => 'Hospital registered successfully', 'user' => $user, 'hospital' => $hospital], 201);
    }

    /**
     * Login user and return token
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required', // Accepts email or phone
            'password' => 'required',
        ]);

        // Try to find user by email or phone
        $user = User::where('email', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['login' => 'Invalid credentials']);
        }

        // Generate Sanctum token
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
        // Revoke only the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
