<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "company" => (string) Str::uuid(),
            "comment" => $this->faker->sentence(10),
            "stars" => 5
        ];
    }
}
