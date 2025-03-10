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

        // Check if the authenticated user has a donor profile
        $donor = Auth::user()->donor;
        if (!$donor) {
            return response()->json(['message' => 'Donor profile not found'], 404);
        }

        // Create the donation application
        $application = DonationApplication::create([
            'donor_id' => $donor->id,  // Get the donor ID from the authenticated user
            'donation_request_id' => $request->donation_request_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'applied',  // Default status for the application
        ]);

        // Return response
        return response()->json([
            'message' => 'Donation application created successfully',
            'application' => $application,
        ]);
    }



    public function viewProfile(Request $request)
    {
        // Get the authenticated donor's profile
        $donor = $request->user()->donor;

        if (!$donor) {
            return response()->json(['message' => 'Donor profile not found'], 404);
        }

        return response()->json($donor);
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
