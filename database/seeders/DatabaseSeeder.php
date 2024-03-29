<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        $admin = \App\Models\User::factory()->withPersonalTeam()->admin()->create([
            'email' => 'admin@example.org'
        ]);

        // Create some user for testing
        $users = \App\Models\User::factory()->withPersonalTeam()->create([
            'email' => 'user@example.org'
        ]);

        // Generate image folder
        Storage::disk('local')->exists('public/images') or Storage::disk('local')->makeDirectory('public/images');

        // Dummy users
        \App\Models\User::factory(20)->withPersonalTeam()->create();

        // Dummy data
        \App\Models\Interest::factory(10)->create();
        \App\Models\Event::factory(150)->create();
        \App\Models\EventGroup::factory(10)->create();

        // Assign some foreign data to each user
        $interests = \App\Models\Interest::all();
        $events = \App\Models\Event::all();
        $eventGroups = \App\Models\EventGroup::all();

        $users = \App\Models\User::all();
        $teams = \App\Models\Team::all();

        // Add kids for each team
        $teams->each(function ($team) {
            $team->kids()->saveMany(\App\Models\Kid::factory(rand(1, 3))->create());
        });

        $users->each(function ($user) use ($interests, $events, $eventGroups) {
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

        // Add some interests and participations to each event
        $events->each(function ($event) use ($interests, $users) {
            $event->interests()->attach(
                $interests->random(rand(1, 3))->pluck('id')->toArray()
            );
            $users->random(rand(1, 3))->each(function ($user) use ($event) {
                \App\Models\Participation::factory()->create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'participation_date' => $event->start_date
                ]);
            });
        });

    }
}
