<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PicklistItem>
 */
class PicklistItemFactory extends Factory
{
        /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PicklistItem::class;

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
            'sequence' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->numberBetween(0, 1),
            'is_system' => $this->faker->numberBetween(0, 1),
        ];
    }
}
