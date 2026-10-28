<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'yailys@example.com')->first();

        if (! $user) {
            return;
        }

        Contact::factory()
            ->count(10)
            ->create([
                'user_id' => $user->id,
            ]);
    }
}
