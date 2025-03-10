<?php

// app/Http/Controllers/DonationHistoryController.php

namespace App\Http\Controllers;

use App\Models\DonationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationHistoryController extends Controller
{
    /**
     * Display a listing of the donation histories for the authenticated donor.
     */
    public function index(Request $request)
    {
        $donor = $request->user()->donor;

        // Return donation history for the logged-in donor only
        return response()->json($donor->donationHistories, 200);
    }

    /**
     * Display the specified donation history.
     */
    public function show(DonationHistory $donationHistory)
    {
        // Ensure that the logged-in user has access to this donation history
        $donor = Auth::user()->donor;

        if ($donationHistory->donor_id !== $donor->id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        return response()->json($donationHistory, 200);
    }
}
