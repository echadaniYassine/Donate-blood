<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    // View hospital profile
    public function viewProfile(Request $request)
    {
        $hospital = $request->user()->hospital;

        return response()->json([
            'hospital' => $hospital,
        ]);
    }

    // Update hospital profile
    public function updateProfile(Request $request)
    {
        $hospital = $request->user()->hospital;

        $request->validate([
            'hospital_name' => 'nullable|string',
            'location' => 'nullable|string',
            'contact_number' => 'nullable|string',
        ]);

        $hospital->update($request->only(['hospital_name', 'location', 'contact_number']));

        return response()->json([
            'message' => 'Hospital profile updated successfully',
            'hospital' => $hospital,
        ]);
    }
}
