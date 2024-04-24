<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\EngRates;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@acms.com',
            'role' => 'admin',
        ]);
        $eng = User::factory()->create([
            'name' => 'Eng',
            'email' => 'eng@acms.com',
            'role' => 'eng',
        ]);
        EngRates::create([
            'meeting_rate',
            'overall_rating',
            'user_id',
        ]);
    }
}
