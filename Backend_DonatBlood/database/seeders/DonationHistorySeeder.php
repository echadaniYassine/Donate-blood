<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationHistory;
use App\Models\Donor;
use App\Models\Recipient;
use App\Models\Hospital;
use Illuminate\Support\Str;

class DonationHistorySeeder extends Seeder
{
    public function run()
    {
        $donors = Donor::all();
        $recipients = Recipient::all();
        $hospitals = Hospital::all();

        if ($donors->isEmpty() || $recipients->isEmpty() || $hospitals->isEmpty()) {
            return;
        }

        DonationHistory::factory(10)->create()->each(function ($history) use ($donors, $recipients, $hospitals) {
            $history->update([
                'donor_id' => $donors->random()->id,
                'recipient_id' => $recipients->random()->id,
                'hospital_id' => $hospitals->random()->id,
                'donation_date' => now(),
                'blood_bag_serial_number' => strtoupper(Str::random(6)),
            ]);
        });
    }
}
