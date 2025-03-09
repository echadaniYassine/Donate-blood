<?php
// database/factories/BloodStockFactory.php

namespace Database\Factories;

use App\Models\BloodStock;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

class BloodStockFactory extends Factory
{
    protected $model = BloodStock::class;

    public function definition()
    {
        return [
            'hospital_id' => Hospital::factory(),
            'blood_type' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'last_updated' => $this->faker->dateTimeThisYear(),
        ];
    }
}
