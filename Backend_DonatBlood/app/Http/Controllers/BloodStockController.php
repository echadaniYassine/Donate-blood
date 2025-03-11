<?php

// app/Http/Controllers/BloodStockController.php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class BloodStockController extends Controller
{
    // Apply auth:sanctum middleware to ensure only authenticated users can access this controller.
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of all blood stocks categorized by blood type.
     */
    public function index()
    {
        // Ensure the logged-in user is a hospital
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Unauthorized action. Only hospitals can view blood stocks.'], 403);
        }

        // Fetch blood stocks grouped by blood type for the specific hospital
        $bloodStocks = BloodStock::where('hospital_id', $hospital->id)->get();

        return response()->json(['blood_stocks' => $bloodStocks]);
    }

    /**
     * Display a specific blood stock entry for a given blood type.
     */
    public function show(Request $request, $bloodType)
    {
        // Ensure the logged-in user is a hospital
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Unauthorized action. Only hospitals can view blood stock.'], 403);
        }

        // Fetch the blood stock for the specific blood type
        $bloodStock = BloodStock::where('hospital_id', $hospital->id)
            ->where('blood_type', $bloodType)
            ->first();

        if (!$bloodStock) {
            return response()->json(['error' => 'Blood stock not found for this type.'], 404);
        }

        return response()->json($bloodStock);
    }

    /**
     * Update the blood stock for a specific hospital and blood type.
     */
    public function update(Request $request, $bloodType)
    {
        // Ensure the logged-in user is a hospital
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Unauthorized action. Only hospitals can update blood stock.'], 403);
        }

        // Validate the blood stock update
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Fetch the blood stock for the specified blood type
        $bloodStock = BloodStock::where('hospital_id', $hospital->id)
            ->where('blood_type', $bloodType)
            ->first();

        if (!$bloodStock) {
            return response()->json(['error' => 'Blood stock not found for this type.'], 404);
        }

        // Update the blood stock quantity
        $bloodStock->update([
            'quantity' => $request->quantity,
            'last_updated' => now(),
        ]);

        return response()->json(['message' => 'Blood stock updated successfully', 'blood_stock' => $bloodStock]);
    }
}
