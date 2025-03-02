<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationRequest;
use App\Models\Donor;
use App\Models\Recipient;
use App\Models\Hospital;

class DonationRequestSeeder extends Seeder
{
    public function run()
    {
        $donors = Donor::all();
        $recipients = Recipient::all();
        $hospitals = Hospital::all();

        if ($donors->isEmpty() || $recipients->isEmpty() || $hospitals->isEmpty()) {
            return;
        }

        DonationRequest::factory(10)->create()->each(function ($request) use ($donors, $recipients, $hospitals) {
            $request->update([
                'recipient_id' => $recipients->random()->id,
                'donor_id' => $donors->random()->id,
                'hospital_id' => $hospitals->random()->id,
            ]);
        });
    }
}
