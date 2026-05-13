<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserRequest>
 */
class UserRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => 'Account Activation',
            'details' => fake()->text(),
            'status' => 'pending',
            'resolved_by' => null,
            'resolved_at' => null,
        ];
    }

    /**
     * State for approved requests.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'resolved_by' => User::factory(['role' => 'admin']),
            'resolved_at' => now(),
        ]);
    }

    /**
     * State for rejected requests.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'resolved_by' => User::factory(['role' => 'admin']),
            'resolved_at' => now(),
        ]);
    }

    /**
     * Set type to role change.
     */
    public function roleChange(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Role Change',
        ]);
    }
}
