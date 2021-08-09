<?php

namespace Database\Factories;

use App\Models\EventGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'long_description' => $this->faker->paragraph()
        ];
    }
}
