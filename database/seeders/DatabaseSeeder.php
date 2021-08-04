<?php

namespace Database\Seeders;

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
        $admin = \App\Models\User::factory()->admin()->create([
            'email' => 'admin@example.org'
        ]);
        \App\Models\Team::factory()->create([
            'user_id' => $admin
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
