<?php

namespace Database\Factories;

use App\Models\Kid;
use Illuminate\Database\Eloquent\Factories\Factory;

class KidFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kid::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-1 year', '-3 months');
        $leaveDate = clone $date;
        $leaveDate->add(new \DateInterval('P3Y'));
        return [
            'name' => $this->faker->unique()->firstName(),
            'entry_date' => $date,
            'leave_date' => $leaveDate
        ];
    }

}
