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
    /**
     * Display a listing of donation applications.
     */
    public function index()
    {
        $applications = DonationApplication::all();
        return response()->json(['applications' => $applications]);
    }

    /**
     * Store a newly created donation application.
     */
    public function store(Request $request)
    {
        // Debugging: Log incoming request data
        Log::info('Received Donation Application Request', ['data' => $request->all()]);

        // Validate request
        $validated = $request->validate([
            'donation_request_id' => 'required|exists:donation_requests,id',
            'appointment_date' => 'required|date|after:today',
        ]);

        // Ensure the authenticated user is a donor
        $user = Auth::user();
        if (!$user) {
            Log::error('Unauthorized access attempt: No authenticated user.');
            return response()->json(['error' => 'Unauthorized access. Please log in.'], 401);
        }

        Log::info('Authenticated User', ['id' => $user->id, 'role' => $user->role]);

        if (!$user->donor) {
            Log::error('Unauthorized action: User is not a donor.', ['user_id' => $user->id]);
            return response()->json(['error' => 'Only donors can apply for donation.'], 403);
        }

        // Ensure the donation request exists
        $donationRequest = DonationRequest::find($request->donation_request_id);
        if (!$donationRequest) {
            Log::error('Donation request not found.', ['request_id' => $request->donation_request_id]);
            return response()->json(['error' => 'Donation request not found.'], 404);
        }

        // Create the donation application
        try {
            $application = DonationApplication::create([
                'donor_id' => $user->donor->id,
                'donation_request_id' => $request->donation_request_id,
                'appointment_date' => $request->appointment_date,
                'status' => 'applied',
            ]);

            Log::info('Donation application created successfully', ['application_id' => $application->id]);

            // Send a notification to the donor
            Notification::create([
                'user_id' => $user->id,
                'message' => "📅 Your application for donation has been received. Your appointment date: {$request->appointment_date}.",
                'type' => 'appointment',
                'is_read' => false,
            ]);

            // Send a notification to the hospital that posted the request
            Notification::create([
                'user_id' => $donationRequest->hospital->user_id, // Hospital's user ID
                'message' => "🩸 A donor has applied to your blood donation request for {$donationRequest->blood_type}.",
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

    /**
     * Display a specific donation application.
     */
    public function show($id)
    {
        try {
            $donationApplication = DonationApplication::findOrFail($id);
            return response()->json($donationApplication);
        } catch (\Exception $e) {
            Log::error('Donation application not found.', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Donation application not found.'], 404);
        }
    }
}
