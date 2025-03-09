<?php

namespace Database\Factories;

use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Support\Str;
use App\Models\DonationHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationHistoryFactory extends Factory {
    protected $model = DonationHistory::class;

    public function definition(): array {
        return [
            'donor_id' => Donor::factory(),
            'hospital_id' => Hospital::factory(),
            'donation_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'volume_donated' => fake()->randomElement([250, 350, 450]), // حجم الدم بالملليلتر
            'status' => 'completed',
            'blood_bag_serial_number' => strtoupper(Str::random(6)), // Add this line to the factory
        ];
    }
}