<?php

namespace App\Http\Controllers;

use App\Models\DonationApplication;
use App\Models\DonationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DonationApplicationController extends Controller
{
    // List all donation applications
    public function index()
    {
        return response()->json(['applications' => DonationApplication::all()]);
    }

    // Create a new donation application
    public function store(Request $request)
    {
        Log::info('Received Donation Application Request', ['data' => $request->all()]);

        $validated = $request->validate([
            'donation_request_id' => 'required|exists:donation_requests,id',
            'appointment_date' => 'required|date|after:today',
        ]);

        $user = Auth::user();
        if (!$user || !$user->donor) {
            return response()->json(['error' => 'Only donors can apply for donation.'], 403);
        }

        $donationRequest = DonationRequest::find($request->donation_request_id);
        if (!$donationRequest) {
            return response()->json(['error' => 'Donation request not found.'], 404);
        }

        try {
            $application = DonationApplication::create([
                'donor_id' => $user->donor->id,
                'donation_request_id' => $request->donation_request_id,
                'appointment_date' => $request->appointment_date,
                'status' => 'applied',
            ]);

            Log::info('Donation application created successfully', ['application_id' => $application->id]);

            // Notify donor
            Notification::create([
                'user_id' => $user->id,
                'message' => "📅 Your application for donation has been received. Appointment date: {$request->appointment_date}.",
                'type' => 'appointment',
                'is_read' => false,
            ]);

            // Notify hospital
            Notification::create([
                'user_id' => $donationRequest->hospital->user_id,
                'message' => "🩸 A donor applied to your blood donation request for {$donationRequest->blood_type}.",
                'type' => 'donation_application',
                'is_read' => false,
            ]);

            return response()->json([
                'message' => 'Donation application created successfully',
                'application' => $application,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating donation application', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    // Show a specific donation application
    public function show($id)
    {
        try {
            return response()->json(DonationApplication::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Donation application not found.'], 404);
        }
    }
}
