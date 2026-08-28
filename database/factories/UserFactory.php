<?php

namespace Database\Factories;

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Role;
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
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Role::ensureSystemRoles();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('01#########'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role_id' => Role::requireBySlug(UserRole::Employee->value)->id,
            'status' => UserStatus::Active,
            'leave_days' => 21,
            'permissions' => [],
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withTwoFactor(): static {}

    public function superAdmin(): static
    {
        return $this->forRole(UserRole::SuperAdmin);
    }

    public function branchAdmin(): static
    {
        return $this->forRole(UserRole::BranchAdmin);
    }

    public function manager(): static
    {
        return $this->forRole(UserRole::Manager);
    }

    public function employee(): static
    {
        return $this->forRole(UserRole::Employee);
    }

    public function forRole(UserRole|Role|string $role): static
    {
        return $this->state(function (array $attributes) use ($role) {
            Role::ensureSystemRoles();

            $model = match (true) {
                $role instanceof Role => $role,
                $role instanceof UserRole => Role::requireBySlug($role->value),
                default => Role::requireBySlug($role),
            };

            return ['role_id' => $model->id];
        });
    }

    public function withPermissions(Permission ...$permissions): static
    {
        return $this->state(fn (array $attributes) => [
            'permissions' => array_map(
                fn (Permission $permission): string => $permission->value,
                $permissions,
            ),
        ]);
    }
}
