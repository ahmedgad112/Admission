<?php

namespace Database\Factories;

use App\Enums\HomePage;
use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->jobTitle();

        return [
            'slug' => Role::uniqueSlug($name),
            'name' => $name,
            'permissions' => UserRole::Employee->defaultPermissionValues(),
            'home_page' => HomePage::Dashboard->value,
            'is_system' => false,
            'sort' => 100,
        ];
    }

    public function system(UserRole $role): static
    {
        return $this->state(fn (array $attributes) => [
            'slug' => $role->value,
            'name' => match ($role) {
                UserRole::SuperAdmin => 'Super Admin',
                UserRole::BranchAdmin => 'Branch Admin',
                UserRole::Manager => 'Manager',
                UserRole::Employee => 'Employee',
            },
            'permissions' => $role === UserRole::SuperAdmin
                ? []
                : $role->defaultPermissionValues(),
            'home_page' => $role->defaultHomePage()->value,
            'is_system' => true,
        ]);
    }
}
