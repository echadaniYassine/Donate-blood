<?php

// app/Http/Controllers/DonorController.php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\DonationApplication;
use App\Models\DonationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;  // This is the base controller class

class DonorController extends Controller // Ensure it extends the Controller base class
{
    public function __construct()
    {
        // Middleware ensures only authenticated users can access these methods
        $this->middleware('auth:sanctum');
    }

    public function applyForDonation(Request $request)
    {
        // Validate the donation application
        $request->validate([
            'donation_request_id' => 'required|exists:donation_requests,id',
            'appointment_date' => 'required|date|after:today',
        ]);

        // Create the donation application
        $application = DonationApplication::create([
            'donor_id' => Auth::user()->donor->id,
            'donation_request_id' => $request->donation_request_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'applied',
        ]);

        return response()->json(['message' => 'Donation application created successfully', 'application' => $application]);
    }

    public function viewProfile(Request $request)
    {
        $donor = $request->user()->donor;

        return response()->json([
            'donor' => $donor,
        ]);
    }

    /**
     * Update donor profile
     */
    public function updateProfile(Request $request)
    {
        $donor = $request->user()->donor;

        // Validate only the fields that are being updated
        $request->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'nullable|string',
        ]);

        $donor->update($request->only(['name', 'phone', 'blood_type', 'address']));

        return response()->json([
            'message' => 'Donor profile updated successfully',
            'donor' => $donor,
        ]);
    }
}
