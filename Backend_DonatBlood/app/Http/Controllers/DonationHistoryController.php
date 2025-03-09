<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonationHistory;

class DonationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(DonationHistory::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'donor_id' => 'required|exists:users,id',
            'blood_type' => 'required|string|max:5',
            'donation_date' => 'required|date',
            'volume_donated' => 'required|integer', 
        ]);
    
        $history = DonationHistory::create($request->all());
    
        return response()->json($history, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DonationHistory $donationHistory)
    {
        return response()->json($donationHistory, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DonationHistory $donationHistory)
    {
        $request->validate([
            'donor_id' => 'sometimes|exists:users,id',
            'blood_type' => 'sometimes|string|max:5',
            'donation_date' => 'sometimes|date',
        ]);

        $donationHistory->update($request->all());

        return response()->json($donationHistory, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DonationHistory $donationHistory)
    {
        $donationHistory->delete();

        return response()->json(null, 204);
    }
}
