<?php

namespace App\Http\Controllers;

use App\Models\DonationHistory;
use App\Models\DonationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\BloodStock; // Add this at the top


class DonationHistoryController extends Controller
{
    /**
     * Mark the donation application as donated and create a donation history record.
     */
    public function markAsDonated($applicationId)
    {
        try {
            // Ensure the user is authenticated
            if (!Auth::check()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }

            // Ensure the user is a hospital
            $hospital = Auth::user()->hospital;
            if (!$hospital) {
                return response()->json(['error' => 'Only hospitals can mark donations as donated'], 403);
            }

            // Find the donation application
            $donationApplication = DonationApplication::find($applicationId);
            if (!$donationApplication) {
                return response()->json(['error' => 'Donation application not found'], 404);
            }

            // Ensure the donation application has a valid donation request
            if (!$donationApplication->donationRequest) {
                return response()->json(['error' => 'Donation request not found'], 404);
            }

            // Ensure the hospital owns the donation request
            if ($donationApplication->donationRequest->hospital_id !== $hospital->id) {
                return response()->json(['error' => 'Unauthorized action'], 403);
            }

            // Ensure the blood_type exists in the donation request
            $bloodType = $donationApplication->donationRequest->blood_type_needed;
            if (!$bloodType) {
                return response()->json(['error' => 'Blood type is missing in the donation request'], 400);
            }

            // Log before creating the donation history
            Log::info('Creating donation history', [
                'donor_id' => $donationApplication->donor_id,
                'donation_request_id' => $donationApplication->donation_request_id,
                'blood_type' => $bloodType,
                'quantity_donated' => $donationApplication->donationRequest->quantity_needed,
                'donation_date' => now(),
            ]);

            // Create a new donation history record
            $donationHistory = DonationHistory::create([
                'donor_id' => $donationApplication->donor_id,
                'donation_request_id' => $donationApplication->donation_request_id,
                'blood_type' => $bloodType,
                'quantity_donated' => $donationApplication->donationRequest->quantity_needed,
                'donation_date' => now(),
            ]);

            // Update the donation application status to 'donated'
            $donationApplication->update(['status' => 'donated']);

            // Update the donation request status to 'fulfilled' and set the donor_id
            $donationApplication->donationRequest->update([
                'status' => 'fulfilled',
                'donor_id' => $donationApplication->donor_id,
            ]);

            // ✅ Update Blood Stock BEFORE returning the response
            $bloodStock = BloodStock::where('hospital_id', $hospital->id)
                ->where('blood_type', $bloodType)
                ->first();

            if ($bloodStock) {
                // Increase the stock quantity
                $bloodStock->increment('quantity', $donationApplication->donationRequest->quantity_needed);
                $bloodStock->update(['last_updated' => now()]);
            } else {
                // If no blood stock exists for this type, create a new record
                BloodStock::create([
                    'hospital_id' => $hospital->id,
                    'blood_type' => $bloodType,
                    'quantity' => $donationApplication->donationRequest->quantity_needed,
                    'last_updated' => now(),
                ]);
            }

            // ✅ Now return the response
            return response()->json([
                'message' => 'Donation marked as donated and history recorded successfully',
                'donation_history' => $donationHistory,
            ], 200);
        } catch (Exception $e) {
            // Log the error details
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
