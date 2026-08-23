<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkforceSeeder extends Seeder
{
    /**
     * Seed the application's workforce demo data.
     */
    public function run(): void
    {
        $branch = Branch::query()->create([
            'name' => 'Cairo Headquarters',
            'latitude' => 30.0444000,
            'longitude' => 31.2357000,
            'radius_meters' => 150,
        ]);

        $shift = Shift::query()->create([
            'name' => 'Standard Day',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'grace_period_minutes' => 15,
        ]);

        $superAdmin = User::factory()->superAdmin()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'branch_id' => $branch->id,
            'shift_id' => $shift->id,
        ]);

        $branchAdmin = User::factory()->branchAdmin()->create([
            'name' => 'Branch Admin',
            'email' => 'branch@example.com',
            'branch_id' => $branch->id,
            'shift_id' => $shift->id,
        ]);

        $engineering = Department::query()->create([
            'name' => 'Engineering',
            'branch_id' => $branch->id,
        ]);

        $operations = Department::query()->create([
            'name' => 'Operations',
            'branch_id' => $branch->id,
        ]);

        $manager = User::factory()->manager()->create([
            'name' => 'Engineering Manager',
            'email' => 'manager@example.com',
            'branch_id' => $branch->id,
            'department_id' => $engineering->id,
            'shift_id' => $shift->id,
        ]);

        $engineering->update(['manager_id' => $manager->id]);

        $employee = User::factory()->employee()->create([
            'name' => 'Ahmed Hassan',
            'email' => 'employee@example.com',
            'branch_id' => $branch->id,
            'department_id' => $engineering->id,
            'shift_id' => $shift->id,
        ]);

        $sara = User::factory()->employee()->create([
            'name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'branch_id' => $branch->id,
            'department_id' => $operations->id,
            'shift_id' => $shift->id,
        ]);

        Task::query()->create([
            'title' => 'Prepare monthly attendance report',
            'description' => 'Compile late arrivals and working hours for the branch.',
            'created_by' => $manager->id,
            'assigned_to' => $employee->id,
            'department_id' => $engineering->id,
            'priority' => TaskPriority::High,
            'status' => TaskStatus::InProgress,
            'due_date' => now()->addDays(5)->toDateString(),
        ]);

        Task::query()->create([
            'title' => 'Review kiosk QR rotation',
            'description' => 'Confirm the 20-second token refresh is working on the lobby display.',
            'created_by' => $branchAdmin->id,
            'assigned_to' => null,
            'department_id' => $operations->id,
            'priority' => TaskPriority::Medium,
            'status' => TaskStatus::Todo,
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $today = AttendanceDay::query()->create([
            'branch_id' => $branch->id,
            'date' => now()->toDateString(),
            'check_in_starts_at' => '08:00:00',
            'check_in_ends_at' => '11:00:00',
            'check_out_starts_at' => '16:00:00',
            'check_out_ends_at' => '19:00:00',
            'created_by' => $superAdmin->id,
        ]);
        $today->staff()->sync([$employee->id, $sara->id, $manager->id]);

        $superAdmin->touch();
    }
}
