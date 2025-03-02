<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index()
    {
        return response()->json(Donor::with('user')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'blood_type' => 'required|string',
            'last_donation_date' => 'nullable|date',
            'availability_status' => 'boolean',
        ]);

        $donor = Donor::create($request->all());
        return response()->json($donor, 201);
    }

    public function show(Donor $donor)
    {
        return response()->json($donor->load('user'));
    }

    public function update(Request $request, Donor $donor)
    {
        $donor->update($request->all());
        return response()->json($donor);
    }

    public function destroy(Donor $donor)
    {
        $donor->delete();
        return response()->json(['message' => 'Donor deleted successfully']);
    }
}
