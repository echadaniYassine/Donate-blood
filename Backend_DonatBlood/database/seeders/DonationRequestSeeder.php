<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationRequest;
use App\Models\Donor;
use App\Models\Hospital;

class DonationRequestSeeder extends Seeder
{
    public function run()
    {
        $donors = Donor::all();
        $hospitals = Hospital::all();

        DonationRequest::factory(10)->create()->each(function ($request) use ($donors, $hospitals) {
            // Set donor_id and hospital_id during creation
            $request->donor_id = $donors->random()->id;
            $request->hospital_id = $hospitals->random()->id;
            $request->save(); // Save the changes
        });
    }
}