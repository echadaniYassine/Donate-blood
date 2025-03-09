<?php

namespace Database\Factories;

use App\Models\Donor;
use App\Models\Hospital;
use App\Models\DonationRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationRequestFactory extends Factory {
    protected $model = DonationRequest::class;

    public function definition(): array {
        return [
            'hospital_id' => Hospital::factory(),
            'donor_id' => Donor::factory(), // Add donor_id here
            'blood_type_needed' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'urgency_level' => fake()->randomElement(['low', 'medium', 'high']),
            'status' => 'pending'
        ];
    }
}
