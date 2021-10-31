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
        // Create default admin user
        $admin = \App\Models\User::factory()->admin()->create([
            'email' => 'admin@example.org'
        ]);

        // Create some user for testing
        $admin = \App\Models\User::factory()->create([
            'email' => 'user@example.org'
        ]);

        // Dummy users
        \App\Models\User::factory(10)->create();

        // Dummy data
        \App\Models\Interest::factory(10)->create();
        \App\Models\Event::factory(10)->create();
        \App\Models\EventGroup::factory(10)->create();

        // Assign some foreign data to each user
        $interests = \App\Models\Interest::all();
        $events = \App\Models\Event::all();
        $eventGroups = \App\Models\EventGroup::all();

        \App\Models\User::all()->each(function ($user) use ($interests, $events, $eventGroups) {
            \App\Models\Team::factory()->create([
                'user_id' => $user->id
            ]);

            $user->interests()->attach(
                $interests->random(rand(1, 3))->pluck('id')->toArray()
            );
            $user->events()->attach(
                $events->random(rand(1, 3))->pluck('id')->toArray()
            );
            $user->eventGroups()->attach(
                $eventGroups->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        // Add some interests to each event
        $events->each(function ($event) use ($interests) {
            $event->interests()->attach(
                $interests->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

    }
}
