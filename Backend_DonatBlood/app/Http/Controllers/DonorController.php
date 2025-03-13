<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\DonationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class DonorController extends Controller
{
    // Apply authentication middleware
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Apply for a donation request
    public function applyForDonation(Request $request)
    {
        $request->validate([
            'donation_request_id' => 'required|exists:donation_requests,id',
            'appointment_date' => 'required|date|after:today',
        ]);

        $donor = Auth::user()->donor;
        if (!$donor) {
            return response()->json(['message' => 'Donor profile not found'], 404);
        }

        $application = DonationApplication::create([
            'donor_id' => $donor->id,
            'donation_request_id' => $request->donation_request_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'applied',
        ]);

        return response()->json([
            'message' => 'Donation application created successfully',
            'application' => $application,
        ]);
    }

    // View donor profile
    public function viewProfile(Request $request)
    {
        $donor = $request->user()->donor;

        if (!$donor) {
            return response()->json(['message' => 'Donor profile not found'], 404);
        }

        return response()->json($donor);
    }

    // Update donor profile
    public function updateProfile(Request $request)
    {
        $donor = $request->user()->donor;

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
