<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    public function index()
    {
        return response()->json(Recipient::with('user')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'requested_blood_type' => 'required|string',
            'urgency_level' => 'required|in:low,medium,high',
        ]);

        $recipient = Recipient::create($request->all());
        return response()->json($recipient, 201);
    }

    public function show(Recipient $recipient)
    {
        return response()->json($recipient->load('user'));
    }

    public function update(Request $request, Recipient $recipient)
    {
        $recipient->update($request->all());
        return response()->json($recipient);
    }

    public function destroy(Recipient $recipient)
    {
        $recipient->delete();
        return response()->json(['message' => 'Recipient deleted successfully']);
    }
}
