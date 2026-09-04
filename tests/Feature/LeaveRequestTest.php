<?php

use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('staff can submit an absence request for themselves', function () {
    $department = Department::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);

    $this->actingAs($employee)
        ->post(route('leave-requests.store'), [
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'type' => LeaveRequestType::Permission->value,
            'reason' => 'Family appointment in the morning.',
        ])
        ->assertRedirect();

    $leaveRequest = LeaveRequest::query()->first();

    expect($leaveRequest)->not->toBeNull()
        ->and($leaveRequest->user_id)->toBe($employee->id)
        ->and($leaveRequest->branch_id)->toBe($employee->branch_id)
        ->and($leaveRequest->department_id)->toBe($employee->department_id)
        ->and($leaveRequest->type)->toBe(LeaveRequestType::Permission)
        ->and($leaveRequest->status)->toBe(LeaveRequestStatus::Pending)
        ->and($leaveRequest->reason)->toBe('Family appointment in the morning.');
});

test('staff cannot view another employees absence request', function () {
    $employee = User::factory()->employee()->create();
    $other = User::factory()->employee()->create();
    $foreign = LeaveRequest::factory()->create(['user_id' => $other->id]);

    $this->actingAs($employee)
        ->get(route('leave-requests.show', $foreign))
        ->assertForbidden();
});

test('managers can approve department absence requests', function () {
    $department = Department::factory()->create();
    $manager = User::factory()->manager()->create([
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    $leaveRequest = LeaveRequest::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $employee->branch_id,
        'department_id' => $employee->department_id,
    ]);

    $this->actingAs($manager)
        ->post(route('leave-requests.review', $leaveRequest), [
            'status' => LeaveRequestStatus::Approved->value,
        ])
        ->assertRedirect(route('leave-requests.show', $leaveRequest));

    expect($leaveRequest->refresh()->status)->toBe(LeaveRequestStatus::Approved)
        ->and($leaveRequest->reviewed_by)->toBe($manager->id)
        ->and($leaveRequest->reviewed_at)->not->toBeNull();
});

test('managers cannot approve their own absence request', function () {
    $department = Department::factory()->create();
    $manager = User::factory()->manager()->create([
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    $leaveRequest = LeaveRequest::factory()->create([
        'user_id' => $manager->id,
        'branch_id' => $manager->branch_id,
        'department_id' => $manager->department_id,
    ]);

    $this->actingAs($manager)
        ->post(route('leave-requests.review', $leaveRequest), [
            'status' => LeaveRequestStatus::Approved->value,
        ])
        ->assertForbidden();
});

test('employees cannot review absence requests', function () {
    $department = Department::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    $teammate = User::factory()->employee()->create([
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    $leaveRequest = LeaveRequest::factory()->create([
        'user_id' => $teammate->id,
        'branch_id' => $teammate->branch_id,
        'department_id' => $teammate->department_id,
    ]);

    $this->actingAs($employee)
        ->post(route('leave-requests.review', $leaveRequest), [
            'status' => LeaveRequestStatus::Approved->value,
        ])
        ->assertForbidden();
});

test('staff can cancel a pending absence request', function () {
    $employee = User::factory()->employee()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $employee->id]);

    $this->actingAs($employee)
        ->post(route('leave-requests.cancel', $leaveRequest))
        ->assertRedirect(route('leave-requests.show', $leaveRequest));

    expect($leaveRequest->refresh()->status)->toBe(LeaveRequestStatus::Cancelled);
});

test('staff cannot cancel an approved absence request', function () {
    $employee = User::factory()->employee()->create();
    $leaveRequest = LeaveRequest::factory()->approved()->create(['user_id' => $employee->id]);

    $this->actingAs($employee)
        ->post(route('leave-requests.cancel', $leaveRequest))
        ->assertForbidden();
});

test('staff cannot request more days than their remaining balance', function () {
    $employee = User::factory()->employee()->create(['leave_days' => 2]);
    LeaveRequest::factory()->approved()->create([
        'user_id' => $employee->id,
        'start_date' => now()->addDays(10)->toDateString(),
        'end_date' => now()->addDays(11)->toDateString(),
    ]);

    $this->actingAs($employee)
        ->post(route('leave-requests.store'), [
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'type' => LeaveRequestType::Permission->value,
            'reason' => 'Need two more days off.',
        ])
        ->assertSessionHasErrors('end_date');
});

test('staff can see their remaining leave days on the request form', function () {
    $employee = User::factory()->employee()->create(['leave_days' => 15]);
    LeaveRequest::factory()->approved()->create([
        'user_id' => $employee->id,
        'start_date' => now()->addDays(8)->toDateString(),
        'end_date' => now()->addDays(10)->toDateString(),
    ]);

    $this->actingAs($employee)
        ->get(route('leave-requests.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('leave-requests/Create')
            ->where('leaveBalance.allocated', 15)
            ->where('leaveBalance.used', 3)
            ->where('leaveBalance.remaining', 12)
        );
});

test('overlapping pending requests are rejected', function () {
    $employee = User::factory()->employee()->create();
    LeaveRequest::factory()->create([
        'user_id' => $employee->id,
        'start_date' => now()->addDay()->toDateString(),
        'end_date' => now()->addDays(3)->toDateString(),
    ]);

    $this->actingAs($employee)
        ->post(route('leave-requests.store'), [
            'start_date' => now()->addDays(2)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'type' => LeaveRequestType::Sick->value,
            'reason' => 'Need another day as well.',
        ])
        ->assertSessionHasErrors('start_date');
});

test('managers see department requests on the index page', function () {
    $department = Department::factory()->create();
    $otherDepartment = Department::factory()->create();
    $manager = User::factory()->manager()->create([
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    $teamRequest = LeaveRequest::factory()->create([
        'user_id' => User::factory()->employee()->create([
            'branch_id' => $department->branch_id,
            'department_id' => $department->id,
        ]),
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    LeaveRequest::factory()->create([
        'user_id' => User::factory()->employee()->create([
            'branch_id' => $otherDepartment->branch_id,
            'department_id' => $otherDepartment->id,
        ]),
        'branch_id' => $otherDepartment->branch_id,
        'department_id' => $otherDepartment->id,
    ]);

    $this->actingAs($manager)
        ->get(route('leave-requests.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('leave-requests/Index')
            ->has('leaveRequests.data', 1)
            ->where('leaveRequests.data.0.id', $teamRequest->id)
            ->where('canReview', true)
        );
});

test('department managers only see leave requests for people under them', function () {
    $department = Department::factory()->create();
    $otherDepartment = Department::factory()->create();
    $manager = User::factory()->manager()->create([
        'branch_id' => $department->branch_id,
        'department_id' => null,
    ]);
    $department->update(['manager_id' => $manager->id]);
    $report = User::factory()->employee()->create([
        'branch_id' => $department->branch_id,
        'department_id' => $department->id,
    ]);
    $outsider = User::factory()->employee()->create([
        'branch_id' => $otherDepartment->branch_id,
        'department_id' => $otherDepartment->id,
    ]);
    $teamRequest = LeaveRequest::factory()->create([
        'user_id' => $report->id,
        'branch_id' => $report->branch_id,
        'department_id' => $report->department_id,
    ]);
    LeaveRequest::factory()->create([
        'user_id' => $outsider->id,
        'branch_id' => $outsider->branch_id,
        'department_id' => $outsider->department_id,
    ]);

    $this->actingAs($manager)
        ->get(route('leave-requests.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('leave-requests/Index')
            ->has('leaveRequests.data', 1)
            ->where('leaveRequests.data.0.id', $teamRequest->id)
            ->where('canReview', true)
        );

    $this->actingAs($manager)
        ->get(route('leave-requests.show', $teamRequest))
        ->assertOk();

    $this->actingAs($manager)
        ->get(route('leave-requests.show', LeaveRequest::query()->where('user_id', $outsider->id)->first()))
        ->assertForbidden();
});
