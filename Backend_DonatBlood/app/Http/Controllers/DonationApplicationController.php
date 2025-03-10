<?php

// app/Http/Controllers/DonationApplicationController.php

namespace App\Http\Controllers;

use App\Models\DonationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Validate the donation application
        $request->validate([
            'donation_request_id' => 'required|exists:donation_requests,id',
            'appointment_date' => 'required|date|after:today',
        ]);

        // Ensure the authenticated user is a donor
        if (!Auth::user()->donor) {
            return response()->json(['error' => 'Only donors can apply for donation'], 403);
        }

        $application = DonationApplication::create([
            'donor_id' => Auth::user()->donor->id,
            'donation_request_id' => $request->donation_request_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'applied',
        ]);

        return response()->json(['message' => 'Donation application created successfully', 'application' => $application], 201);
    }

    /**
     * Display a specific donation application.
     */
    public function show(DonationApplication $donationApplication)
    {
        return response()->json($donationApplication);
    }
}
