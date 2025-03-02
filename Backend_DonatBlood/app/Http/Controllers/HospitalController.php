<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index()
    {
        return response()->json(Hospital::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required|string',
        ]);

        $hospital = Hospital::create($request->all());
        return response()->json($hospital, 201);
    }

    public function show(Hospital $hospital)
    {
        return response()->json($hospital);
    }

    public function update(Request $request, Hospital $hospital)
    {
        $hospital->update($request->all());
        return response()->json($hospital);
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();
        return response()->json(['message' => 'Hospital deleted successfully']);
    }
}
