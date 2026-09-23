<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'is_platform_admin' => false,
            'organization_key' => null,
            'module_access' => null,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function platformAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_platform_admin' => true,
            'organization_key' => 'global-synergia-group',
            'module_access' => ['remodeling', 'capital-funding', 'bond-agency', 'clinical-recruitment', 'cnc-quote'],
        ]);
    }

    public function kgEmployee(): static
    {
        return $this->moduleUser('k-and-g-art-design', ['remodeling', 'cnc-quote']);
    }

    public function fundingClientUser(): static
    {
        return $this->moduleUser('j-funding-capital', ['capital-funding']);
    }

    public function bondClientUser(): static
    {
        return $this->moduleUser('southernmost-surety', ['bond-agency']);
    }

    public function researchClientUser(): static
    {
        return $this->moduleUser('ara-professionals', ['clinical-recruitment']);
    }

    public function medisanaUser(): static
    {
        return $this->moduleUser('medisana-research-center', ['clinical-recruitment']);
    }

    public function moduleUser(string $organizationKey, array $modules): static
    {
        return $this->state(fn (array $attributes) => [
            'is_platform_admin' => false,
            'organization_key' => $organizationKey,
            'module_access' => array_values($modules),
        ]);
    }
}
