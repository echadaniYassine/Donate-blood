<?php

namespace Database\Factories;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HospitalFactory extends Factory
{
    protected $model = Hospital::class;

    public function definition(): array
    {
        $user = User::factory()->state(['role' => 'hospital'])->create();

        return [
            'user_id' => $user->id,
            'name' => $this->faker->company(),
            'location' => $this->faker->address(),
            'contact_email' => $this->faker->unique()->companyEmail(),
            'contact_phone' => $this->faker->unique()->phoneNumber(),
        ];
    }
}
