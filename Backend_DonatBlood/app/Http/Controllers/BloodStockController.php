<?php

// app/Http/Controllers/BloodStockController.php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use Illuminate\Http\Request;

class BloodStockController extends Controller
{
    /**
     * Display a listing of all blood stocks.
     */
    public function index()
    {
        $bloodStocks = BloodStock::all();

        return response()->json(['blood_stocks' => $bloodStocks]);
    }

    /**
     * Display a specific blood stock entry.
     */
    public function show(BloodStock $bloodStock)
    {
        return response()->json($bloodStock);
    }

    /**
     * Update the blood stock for a specific hospital and blood type.
     */
    public function update(Request $request, BloodStock $bloodStock)
    {
        // Validate the blood stock update
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $bloodStock->update([
            'quantity' => $request->quantity,
            'last_updated' => now(),
        ]);

        return response()->json(['message' => 'Blood stock updated successfully', 'blood_stock' => $bloodStock]);
    }
}
