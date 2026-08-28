<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\RolePermissionSet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RolePermissionSet>
 */
class RolePermissionSetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => UserRole::Manager,
            'permissions' => UserRole::Manager->defaultPermissionValues(),
            'home_page' => UserRole::Manager->defaultHomePage()->value,
        ];
    }
}
