<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donor;
use App\Models\Recipient;
use App\Models\Hospital;
use App\Models\DonationRequest;
use App\Models\DonationHistory;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Users First
        User::factory(20)->create();

        // Create Donors & Recipients
        $donors = Donor::factory(10)->create();
        $recipients = Recipient::factory(10)->create();
        $hospitals = Hospital::factory(5)->create();

        // Create Donation Requests (Ensure valid foreign keys)
        DonationRequest::factory(10)->create()->each(function ($request) use ($donors, $recipients, $hospitals) {
            $request->update([
                'recipient_id' => $recipients->random()->id,
                'donor_id' => $donors->random()->id,
                'hospital_id' => $hospitals->random()->id,
            ]);
        });

        // Create Donation Histories (Ensure valid foreign keys)
        DonationHistory::factory(10)->create()->each(function ($history) use ($donors, $recipients, $hospitals) {
            $history->update([
                'donor_id' => $donors->random()->id,
                'recipient_id' => $recipients->random()->id,
                'hospital_id' => $hospitals->random()->id,
                'donation_date' => now(),
                'blood_bag_serial_number' => strtoupper(Str::random(6)),
            ]);
        });

        // Create Notifications
        Notification::factory(10)->create();
    }
}
