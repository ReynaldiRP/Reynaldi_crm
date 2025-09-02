<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomElement(User::pluck('id')),
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'needs' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['new', 'contacted', 'qualified', 'lost']),
            'converted_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'converted_to_customer_id' => $this->faker->randomElement(Customer::pluck('id'))
        ];
    }
}
