<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->dateTimeBetween('now', '+7 days'),
            'description' => $this->faker->paragraphs(),
            'total_hire_count' => $this->faker->numberBetween(2, 5),
            'salary' => $this->faker->randomNumber(6),
            'start_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'start_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'interview_availability' => $this->faker->dateTimeBetween('now', '+2 weeks'),
        ];
    }

}
