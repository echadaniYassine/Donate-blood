<?php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class BloodStockController extends Controller
{
    // Enforce authentication using Sanctum middleware
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // List all blood stocks for the authenticated hospital
    public function index()
    {
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Only hospitals can view blood stocks.'], 403);
        }

        return response()->json(['blood_stocks' => BloodStock::where('hospital_id', $hospital->id)->get()]);
    }

    // Show blood stock details for a specific blood type
    public function show($bloodType)
    {
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Only hospitals can view blood stock.'], 403);
        }

        $bloodStock = BloodStock::where('hospital_id', $hospital->id)->where('blood_type', $bloodType)->first();

        return $bloodStock
            ? response()->json($bloodStock)
            : response()->json(['error' => 'Blood stock not found.'], 404);
    }

    // Update the quantity of a specific blood type
    public function update(Request $request, $bloodType)
    {
        $hospital = Auth::user()->hospital;
        if (!$hospital) {
            return response()->json(['error' => 'Only hospitals can update blood stock.'], 403);
        }

        $request->validate(['quantity' => 'required|integer|min:1']);

        $bloodStock = BloodStock::where('hospital_id', $hospital->id)->where('blood_type', $bloodType)->first();
        if (!$bloodStock) {
            return response()->json(['error' => 'Blood stock not found.'], 404);
        }

        $bloodStock->update(['quantity' => $request->quantity, 'last_updated' => now()]);

        return response()->json(['message' => 'Blood stock updated successfully', 'blood_stock' => $bloodStock]);
    }
}
