<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = $this->faker->dateTimeThisYear('+1 month');
        $end = clone $start;
        $end->add(new \DateInterval('PT2H'));

        return [
            'event_group_id' => EventGroup::factory(),
            'name' => $this->faker->words(3, true),
            'start_date' => $start,
            'end_date' => $end,
            'approximate_hours' => $this->faker->numberBetween(1, 5),
            'image' => $this->faker->image(storage_path('app/public').'/images/',400,300, null, false),
            'quota' => $this->faker->numberBetween(1,20),
            'description' => $this->faker->sentence(),
            'long_description' => $this->faker->paragraph()
        ];
    }
}
