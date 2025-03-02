<?php

namespace Database\Factories;

use App\Models\DonationRequest;
use App\Models\Hospital;
use App\Models\Recipient;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationRequestFactory extends Factory
{
    protected $model = DonationRequest::class;

    public function definition()
    {
        return [
            'recipient_id' => Recipient::factory(), // ✅ Ensure recipient exists
            'donor_id' => null, // ✅ Donor is assigned later
            'hospital_id' => Hospital::factory(),
            'request_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'completed', 'canceled']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
