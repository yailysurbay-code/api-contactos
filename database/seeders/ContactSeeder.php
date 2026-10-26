<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\User;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'yailys@example.com')->first();

        if (!$user) {
            return;
        }

        Contact::factory()
            ->count(10)
            ->create([
                'user_id' => $user->id,
            ]);
    }
}