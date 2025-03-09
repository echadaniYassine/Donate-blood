<?php

// app/Http/Controllers/BloodStockController.php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use Illuminate\Http\Request;

class BloodStockController extends Controller
{
    public function updateStock(Request $request)
    {
        // Validate the blood stock update
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'blood_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $bloodStock = BloodStock::updateOrCreate(
            ['hospital_id' => $request->hospital_id, 'blood_type' => $request->blood_type],
            ['quantity' => $request->quantity, 'last_updated' => now()]
        );

        return response()->json(['message' => 'Blood stock updated successfully', 'blood_stock' => $bloodStock]);
    }

    public function viewStock($hospitalId)
    {
        $bloodStocks = BloodStock::where('hospital_id', $hospitalId)->get();

        return response()->json(['blood_stocks' => $bloodStocks]);
    }
}
