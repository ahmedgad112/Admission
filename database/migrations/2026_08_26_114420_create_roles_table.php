<?php

use App\Enums\HomePage;
use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->json('permissions')->default('[]');
            $table->string('home_page')->default(HomePage::Dashboard->value);
            $table->boolean('is_system')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        $overrides = [];

        if (Schema::hasTable('role_permission_sets')) {
            $overrides = DB::table('role_permission_sets')
                ->get()
                ->keyBy('role')
                ->all();
        }

        $now = now();
        $sort = 0;

        foreach (UserRole::cases() as $role) {
            $override = $overrides[$role->value] ?? null;
            $permissions = $role === UserRole::SuperAdmin
                ? []
                : ($override !== null
                    ? json_decode($override->permissions ?? '[]', true)
                    : $role->defaultPermissionValues());
            $homePage = $role === UserRole::SuperAdmin
                ? HomePage::Dashboard->value
                : ($override->home_page ?? $role->defaultHomePage()->value);

            DB::table('roles')->insert([
                'slug' => $role->value,
                'name' => match ($role) {
                    UserRole::SuperAdmin => 'Super Admin',
                    UserRole::BranchAdmin => 'Branch Admin',
                    UserRole::Manager => 'Manager',
                    UserRole::Employee => 'Employee',
                },
                'permissions' => json_encode(array_values($permissions ?? [])),
                'home_page' => $homePage,
                'is_system' => true,
                'sort' => $sort++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $roleIds = DB::table('roles')->pluck('id', 'slug');
        $employeeId = (int) $roleIds[UserRole::Employee->value];

        Schema::table('users', function (Blueprint $table) use ($employeeId) {
            $table->foreignId('role_id')
                ->default($employeeId)
                ->after('password')
                ->constrained('roles')
                ->restrictOnDelete();
        });

        foreach ($roleIds as $slug => $id) {
            DB::table('users')->where('role', $slug)->update(['role_id' => $id]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 32)->default('employee')->after('password');
        });

        $roleSlugs = DB::table('roles')->pluck('slug', 'id');

        foreach ($roleSlugs as $id => $slug) {
            DB::table('users')->where('role_id', $id)->update(['role' => $slug]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->index('role');
        });

        Schema::dropIfExists('roles');
    }
};
