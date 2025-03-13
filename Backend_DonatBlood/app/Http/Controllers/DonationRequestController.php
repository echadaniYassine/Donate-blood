<?php

namespace App\Http\Controllers;

use App\Models\DonationRequest;
use App\Models\Notification;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationRequestController extends Controller
{
    // Get all donation requests
    public function index()
    {
        $requests = DonationRequest::all();
        return response()->json(['donation_requests' => $requests]);
    }

    // Create a new donation request
    public function store(Request $request)
    {
        // Ensure only hospitals can create requests
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        // Validate input
        $request->validate([
            'blood_type_needed' => 'required|string|in:A-,A+,B+,B-,AB+,AB-,O+,O-',
            'quantity_needed' => 'required|integer|min:1',
        ]);

        // Create donation request
        $donationRequest = DonationRequest::create([
            'hospital_id' => $hospital->id,
            'blood_type_needed' => $request->blood_type_needed,
            'quantity_needed' => $request->quantity_needed,
            'urgency_level' => $request->urgency_level ?? 'low', // Default to low
            'status' => $request->status ?? 'pending', // Default status
            'posted_at' => $request->posted_at ?? now(), // Default to current timestamp
        ]);


        // Notify matching donors
        $donors = Donor::where('blood_type', $request->blood_type_needed)->get();
        foreach ($donors as $donor) {
            Notification::create([
                'user_id' => $donor->user_id,
                'message' => "🩸 Urgent: A hospital needs your blood type ({$request->blood_type_needed}).",
                'type' => 'donation_request',
                'is_read' => false,
            ]);
        }

        return response()->json([
            'message' => 'Donation request created successfully',
            'donation_request' => $donationRequest,
        ], 201);
    }

    // Get a specific donation request
    public function show(DonationRequest $donationRequest)
    {
        return response()->json(['donation_request' => $donationRequest]);
    }

    // Update an existing donation request
    public function update(Request $request, DonationRequest $donationRequest)
    {
        // Validate input
        $request->validate([
            'blood_type_needed' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_needed' => 'nullable|integer|min:1',
            'urgency_level' => 'nullable|string|in:low,medium,high',
            'status' => 'nullable|string|in:pending,fulfilled,canceled',
            'posted_at' => 'nullable|date',
        ]);

        // Ensure only the request-owning hospital can update
        $hospital = Auth::user()->hospital;
        if ($donationRequest->hospital_id !== $hospital->id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        // Update donation request
        $donationRequest->update($request->only([
            'blood_type_needed',
            'quantity_needed',
            'urgency_level',
            'status',
            'posted_at',
        ]));
        return response()->json([
            'message' => 'Donation request updated successfully',
            'donation_request' => $donationRequest,
        ]);
    }

    // Delete a donation request
    public function destroy(DonationRequest $donationRequest)
    {
        // Ensure only the request-owning hospital can delete
        $hospital = Auth::user()->hospital;
        if ($donationRequest->hospital_id !== $hospital->id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        // Delete request
        $donationRequest->delete();

        return response()->json(['message' => 'Donation request deleted successfully']);
    }
}
