<?php

namespace App\Http\Controllers;

use App\Models\DonationHistory;
use App\Models\DonationApplication;
use App\Models\Notification;
use App\Models\BloodStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class DonationHistoryController extends Controller
{
    // Mark donation as completed and record history
    public function markAsDonated($applicationId)
    {
        try {
            // Ensure user authentication and hospital role
            if (!Auth::check()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }

            $hospital = Auth::user()->hospital;
            if (!$hospital) {
                return response()->json(['error' => 'Only hospitals can mark donations as donated'], 403);
            }

            // Validate donation application
            $donationApplication = DonationApplication::find($applicationId);
            if (!$donationApplication || !$donationApplication->donationRequest) {
                return response()->json(['error' => 'Donation application or request not found'], 404);
            }

            // Ensure hospital owns the donation request
            if ($donationApplication->donationRequest->hospital_id !== $hospital->id) {
                return response()->json(['error' => 'Unauthorized action'], 403);
            }

            // Validate blood type
            $bloodType = $donationApplication->donationRequest->blood_type_needed;
            if (!$bloodType) {
                return response()->json(['error' => 'Blood type missing in donation request'], 400);
            }

            // Record donation history
            $donationHistory = DonationHistory::create([
                'donor_id' => $donationApplication->donor_id,
                'donation_request_id' => $donationApplication->donation_request_id,
                'blood_type' => $bloodType,
                'quantity_donated' => $donationApplication->donationRequest->quantity_needed,
                'donation_date' => now(),
            ]);

            // Update donation application status
            $donationApplication->update(['status' => 'donated']);

            // Notify donor
            Notification::create([
                'user_id' => $donationApplication->donor->user_id,
                'message' => "Thank you for your generous blood donation! ❤️",
                'type' => 'success',
                'is_read' => false,
            ]);

            // Update donation request status
            $donationApplication->donationRequest->update([
                'status' => 'fulfilled',
                'donor_id' => $donationApplication->donor_id,
            ]);

            // Update blood stock
            $bloodStock = BloodStock::where('hospital_id', $hospital->id)
                ->where('blood_type', $bloodType)
                ->first();

            if ($bloodStock) {
                $bloodStock->increment('quantity', $donationApplication->donationRequest->quantity_needed);
                $bloodStock->update(['last_updated' => now()]);
            } else {
                BloodStock::create([
                    'hospital_id' => $hospital->id,
                    'blood_type' => $bloodType,
                    'quantity' => $donationApplication->donationRequest->quantity_needed,
                    'last_updated' => now(),
                ]);
            }

            return response()->json([
                'message' => 'Donation marked as donated and history recorded successfully',
                'donation_history' => $donationHistory,
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in markAsDonated:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
