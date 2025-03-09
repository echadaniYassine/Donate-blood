<?php

// app/Http/Controllers/DonationApplicationController.php

namespace App\Http\Controllers;

use App\Models\DonationApplication;
use Illuminate\Http\Request;

class DonationApplicationController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        // Validate the status update
        $request->validate([
            'status' => 'required|in:applied,accepted,rejected,completed',
        ]);

        $application = DonationApplication::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return response()->json(['message' => 'Application status updated successfully', 'application' => $application]);
    }

    public function viewApplications($donorId)
    {
        $applications = DonationApplication::where('donor_id', $donorId)->get();

        return response()->json(['applications' => $applications]);
    }
}
