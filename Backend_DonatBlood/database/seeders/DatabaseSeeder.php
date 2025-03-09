<?php

namespace Database\Seeders;

use App\Models\DonationApplication;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DonorSeeder::class,
            HospitalSeeder::class,
            DonationRequestSeeder::class,
            DonationHistorySeeder::class,
            NotificationSeeder::class,
            BloodStockSeeder::class,
            DonationApplicationSeeder::class
        ]);
    }
}
