<?php

// app/Http/Controllers/DonationRequestController.php

namespace App\Http\Controllers;

use App\Models\DonationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Donor;

class DonationRequestController extends Controller
{
    /**
     * Display a listing of the donation requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = DonationRequest::all();
        return response()->json(['donation_requests' => $requests]);
    }

    /**
     * Store a newly created donation request in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Ensure the logged-in user is a hospital
        $hospital = Auth::user()->hospital;

        if (!$hospital) {
            return response()->json(['error' => 'Unauthorized action. Only hospitals can create donation requests.'], 403);
        }

        // Validate donation request data
        $request->validate([
            'blood_type_needed' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_needed' => 'required|integer|min:1',
        ]);

        // Create a new donation request
        $donationRequest = DonationRequest::create([
            'hospital_id' => $hospital->id,
            'blood_type' => $request->blood_type,
            'quantity_needed' => $request->quantity_needed,
        ]);

        // ✅ Notify all donors with the matching blood type
        $donors = Donor::where('blood_type', $request->blood_type_needed)->get();
        foreach ($donors as $donor) {
            Notification::create([
                'user_id' => $donor->user_id, // Donor's user ID
                'message' => "🩸 Urgent: A hospital needs your blood type ({$request->blood_type_needed}). Please consider donating!",
                'type' => 'donation_request',
                'is_read' => false,
            ]);
        }

        return response()->json([
            'message' => 'Donation request created successfully',
            'donation_request' => $donationRequest,
        ], 201);
    }


    /**
     * Display the specified donation request.
     *
     * @param \App\Models\DonationRequest $donationRequest
     * @return \Illuminate\Http\Response
     */
    public function show(DonationRequest $donationRequest)
    {
        return response()->json(['donation_request' => $donationRequest]);
    }

    /**
     * Update the specified donation request in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\DonationRequest $donationRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DonationRequest $donationRequest)
    {
        // Validate donation request data
        $request->validate([
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_needed' => 'nullable|integer|min:1',
        ]);

        // Only allow hospital that owns the donation request to update
        $hospital = Auth::user()->hospital;
        if ($donationRequest->hospital_id !== $hospital->id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        // Update the donation request
        $donationRequest->update($request->only(['blood_type', 'quantity_needed']));

        return response()->json([
            'message' => 'Donation request updated successfully',
            'donation_request' => $donationRequest,
        ]);
    }

    /**
     * Remove the specified donation request from the database.
     *
     * @param \App\Models\DonationRequest $donationRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(DonationRequest $donationRequest)
    {
        // Only allow hospital that owns the donation request to delete
        $hospital = Auth::user()->hospital;
        if ($donationRequest->hospital_id !== $hospital->id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        // Delete the donation request
        $donationRequest->delete();

        return response()->json(['message' => 'Donation request deleted successfully']);
    }
}
