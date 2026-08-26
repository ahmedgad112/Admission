<?php

use App\Models\Attendance;
use App\Models\AttendanceDay;
use App\Models\Branch;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Shift;
use App\Models\Task;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('list pages return the records shown as cards', function () {
    $branch = Branch::factory()->create(['name' => 'Card Clinic']);
    $department = Department::factory()->create([
        'name' => 'Nursing',
        'branch_id' => $branch->id,
    ]);
    $shift = Shift::factory()->create(['name' => 'Morning']);
    $admin = User::factory()->superAdmin()->create([
        'name' => 'Card Admin',
        'branch_id' => $branch->id,
        'department_id' => $department->id,
        'shift_id' => $shift->id,
    ]);
    $employee = User::factory()->employee()->create([
        'name' => 'Card Employee',
        'email' => 'card.employee@example.com',
        'branch_id' => $branch->id,
        'department_id' => $department->id,
        'shift_id' => $shift->id,
    ]);
    $day = AttendanceDay::factory()->create([
        'branch_id' => $branch->id,
        'created_by' => $admin->id,
    ]);
    $day->staff()->sync([$employee->id]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
    ]);
    $task = Task::factory()->assignedTo($employee)->create([
        'title' => 'Card task',
        'created_by' => $admin->id,
        'department_id' => $department->id,
    ]);
    $leave = LeaveRequest::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);

    $this->actingAs($admin)
        ->get(route('staff.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('staff/Index')
            ->has('staff.data', 2)
            ->where('staff.data.1.name', 'Card Employee')
            ->where('staff.data.1.email', 'card.employee@example.com')
            ->where('staff.data.1.branch.name', 'Card Clinic')
            ->where('staff.data.1.department.name', 'Nursing')
            ->where('staff.data.1.shift.name', 'Morning')
            ->has('staff.data.1.leave_days')
            ->has('staff.data.1.role')
            ->has('staff.data.1.status'));

    $this->actingAs($admin)
        ->get(route('branches.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('branches/Index')
            ->has('branches.data', 1)
            ->where('branches.data.0.name', 'Card Clinic'));

    $this->actingAs($admin)
        ->get(route('shifts.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shifts/Index')
            ->has('shifts.data', 1)
            ->where('shifts.data.0.name', 'Morning')
            ->has('shifts.data.0.start_time')
            ->has('shifts.data.0.end_time')
            ->has('shifts.data.0.grace_period_minutes')
            ->has('shifts.data.0.staff_count'));

    $this->actingAs($admin)
        ->get(route('leave-requests.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('leave-requests/Index')
            ->has('leaveRequests.data', 1)
            ->where('leaveRequests.data.0.id', $leave->id)
            ->where('leaveRequests.data.0.user.name', 'Card Employee')
            ->where('leaveRequests.data.0.department.name', 'Nursing')
            ->has('leaveRequests.data.0.start_date')
            ->has('leaveRequests.data.0.type')
            ->has('leaveRequests.data.0.status'));

    $this->actingAs($admin)
        ->get(route('attendance.days.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/days/Index')
            ->has('days.data', 1)
            ->where('days.data.0.id', $day->id)
            ->where('days.data.0.branch.name', 'Card Clinic')
            ->where('days.data.0.staff.0.id', $employee->id));

    $this->actingAs($admin)
        ->get(route('attendance.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/Index')
            ->where('canRecord', true)
            ->has('people', 1)
            ->where('people.0.name', 'Card Employee'));

    $this->actingAs($admin)
        ->get(route('tasks.index'))
        ->assertOk()
        ->assertInertia(function (Assert $page) use ($task): void {
            $page->component('tasks/Index')
                ->has('tasks.data', 1)
                ->where('tasks.data.0.id', $task->id)
                ->where('tasks.data.0.title', 'Card task')
                ->has('tasks.data.0.assignees')
                ->has('tasks.data.0.priority')
                ->has('tasks.data.0.status');

            /** @var array<string, string> $translations */
            $translations = $page->toArray()['props']['translations'];

            expect($translations['tasks.empty'])->toBe('No tasks yet.');
        });

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('metrics.today_attendance', 1)
            ->where('metrics.today_attendance.0.user.name', 'Card Employee')
            ->where('metrics.today_attendance.0.branch.name', 'Card Clinic')
            ->has('metrics.today_attendance.0.status')
            ->has('metrics.today_attendance.0.check_in'));
});
