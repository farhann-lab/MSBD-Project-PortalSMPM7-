<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password123'),
            'role' => 'siswa',
            'is_active' => true,
            'wajib_ganti_password' => false,
            'is_admin_utama' => false,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_admin_utama' => true,
        ]);
    }
}
