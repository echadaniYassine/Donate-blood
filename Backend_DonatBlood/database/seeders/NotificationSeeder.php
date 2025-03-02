<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        Notification::factory(10)->create()->each(function ($notification) use ($users) {
            $notification->update([
                'user_id' => $users->random()->id,
            ]);
        });
    }
}
