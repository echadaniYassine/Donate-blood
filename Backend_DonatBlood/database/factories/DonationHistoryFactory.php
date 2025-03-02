<?php

namespace Database\Factories;

use App\Models\DonationHistory;
use App\Models\Donor;
use App\Models\Recipient;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationHistoryFactory extends Factory
{
    protected $model = DonationHistory::class;

    public function definition()
    {
        return [
            'donor_id' => Donor::factory(),
            'recipient_id' => Recipient::factory(),
            'hospital_id' => Hospital::factory(),
            'donation_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'blood_bag_serial_number' => strtoupper($this->faker->unique()->bothify('??#####')), // ✅ Ensures consistency
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
