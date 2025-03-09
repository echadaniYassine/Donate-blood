<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationHistory;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Support\Str;

class DonationHistorySeeder extends Seeder
{
    public function run()
    {
        $donors = Donor::all();
        $hospitals = Hospital::all();

        DonationHistory::factory(10)->create()->each(function ($history) use ($donors, $hospitals) {
            $history->update([
                'donor_id' => $donors->random()->id,
                'hospital_id' => $hospitals->random()->id,
                'donation_date' => now(),
                'blood_bag_serial_number' => strtoupper(Str::random(6)), // Ensure this is set
            ]);
        });
    }
}