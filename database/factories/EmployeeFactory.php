<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => 'EMP-' . str_pad($this->faker->unique()->randomNumber(4), 4, '0', STR_PAD_LEFT),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'address' => $this->faker->address(),
            'department' => $this->faker->jobTitle(),
            'position' => $this->faker->jobTitle(),
            'date_hired' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'status' => $this->faker->randomElement(['active', 'probationary', 'contractual']),
            'profile_completed' => true,
        ];
    }

    /**
     * Indicate that the employee should be on probation.
     */
    public function probationary(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'probationary',
        ]);
    }

    /**
     * Indicate that the employee should be contractual.
     */
    public function contractual(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'contractual',
            'contract_expiry' => now()->addMonths(6),
        ]);
    }
}
