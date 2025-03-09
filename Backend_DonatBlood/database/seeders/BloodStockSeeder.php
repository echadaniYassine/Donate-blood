<?php

// database/seeders/BloodStockSeeder.php

namespace Database\Seeders;

use App\Models\BloodStock;
use Illuminate\Database\Seeder;

class BloodStockSeeder extends Seeder
{
    public function run()
    {
        BloodStock::factory(5)->create();
    }
}
