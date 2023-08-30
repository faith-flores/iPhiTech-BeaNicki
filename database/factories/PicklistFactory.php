<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Picklist>
 */
class PicklistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Picklist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'label' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'identifier' => $this->faker->slug(1),
            'is_tag' => $this->faker->numberBetween(0, 1),
        ];
    }
}
