<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $account_type = $this->faker->randomElement([Account::ACCOUNT_TYPE_PERSONAL, Account::ACCOUNT_TYPE_BUSINESS]);

        $data = [
            'account_type' => $account_type,
            'email' => $this->faker->companyEmail,
            'is_active' => $this->faker->boolean(95),
        ];

        if ($account_type === Account::ACCOUNT_TYPE_BUSINESS) {
            $data['company_name'] = $this->faker->company;
            $data['company_phone'] = $this->faker->phoneNumber;
            $data['web_url'] = $this->faker->url;
        }

        return $data;
    }
}
