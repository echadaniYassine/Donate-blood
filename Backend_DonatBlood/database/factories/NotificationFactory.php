<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'message' => $this->faker->sentence(10),
            'read_at' => $this->faker->optional()->dateTime(),
            'read_at' => $this->faker->boolean(50) ? now() : null, // ✅ Only set read_at sometimes
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
