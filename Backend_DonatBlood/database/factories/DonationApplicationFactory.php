<?php

// database/factories/DonationApplicationFactory.php

namespace Database\Factories;

use App\Models\DonationApplication;
use App\Models\Donor;
use App\Models\DonationRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationApplicationFactory extends Factory
{
    protected $model = DonationApplication::class;

    public function definition()
    {
        return [
            'donor_id' => Donor::factory(),
            'donation_request_id' => DonationRequest::factory(),
            'status' => $this->faker->randomElement(['applied', 'accepted', 'rejected', 'completed']),
            'appointment_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
