<?php

namespace Database\Seeders;

use App\Models\EngRates;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@acms.com',
            'role' => 'user',
        ]);
        User::factory()->create([
            'name' => 'Eng',
            'email' => 'eng@acms.com',
            'role' => 'eng',
        ]);
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@acms.com',
            'role' => 'admin',
        ]);
        ////////////////////////////////////////////////
        User::factory(50)->create();

        // Create 50 Eng users
        $engs = User::factory(50)->create([
            'role' => 'eng',
            'balance' => random_int(500, 10000),
        ]);

        // Create EngRates for each Eng user
        $engs->each(function ($eng) {
            EngRates::create([
                'meeting_rate' => 10,
                'overall_rating' => 10,
                'eng_id' => $eng->id,
            ]);
        });
    }
}
