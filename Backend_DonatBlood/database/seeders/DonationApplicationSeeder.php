<?php

// database/seeders/DonationApplicationSeeder.php

namespace Database\Seeders;

use App\Models\DonationApplication;
use Illuminate\Database\Seeder;

class DonationApplicationSeeder extends Seeder
{
    public function run()
    {
        DonationApplication::factory(10)->create();
    }
}
