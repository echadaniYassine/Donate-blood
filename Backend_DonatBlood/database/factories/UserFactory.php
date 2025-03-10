<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'role' => $this->faker->randomElement(['donor', 'hospital', 'admin']), 
            'password' => Hash::make('password'),
        ];
    }
}
