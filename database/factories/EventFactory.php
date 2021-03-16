<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'status' => $this->faker->randomElement(['pending', 'approved']),
            'description' => $this->faker->text,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
