<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'avatar' => fake()->imageUrl(),
            'user_id' => User::factory(),
        ];
    }
}
