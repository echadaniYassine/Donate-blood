<?php

// app/Http/Controllers/BloodStockController.php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;  // This is the base controller class

class BloodStockController extends Controller
{
    // Apply auth:sanctum middleware to ensure only authenticated users can access this controller.
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of all blood stocks.
     */
    public function index()
    {
        // Ensure the logged-in user is a hospital
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Unauthorized action. Only hospitals can view blood stocks.'], 403);
        }

        // Fetch all blood stocks
        $bloodStocks = BloodStock::all();

        return response()->json(['blood_stocks' => $bloodStocks]);
    }

    /**
     * Display a specific blood stock entry.
     */
    public function show(BloodStock $bloodStock)
    {
        // Ensure the logged-in user is a hospital
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Unauthorized action. Only hospitals can view blood stock.'], 403);
        }

        return response()->json($bloodStock);
    }

    /**
     * Update the blood stock for a specific hospital and blood type.
     */
    public function update(Request $request, BloodStock $bloodStock)
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

        // Update blood stock
        $bloodStock->update([
            'quantity' => $request->quantity,
            'last_updated' => now(),
        ]);

        return response()->json(['message' => 'Blood stock updated successfully', 'blood_stock' => $bloodStock]);
    }
}
