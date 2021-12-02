<?php

namespace Database\Factories;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'idea_id' =>Idea::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function existing()
    {
        return $this->state(fn (array $attributes) => [
            'idea_id' => $this->faker->numberBetween(1, 100),
            'user_id' => $this->faker->numberBetween(1, 20),
        ]);
    }
}
