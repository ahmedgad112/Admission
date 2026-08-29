<?php

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Shift;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function recordPayload(User $employee, array $overrides = []): array
{
    return [
        'date' => now()->toDateString(),
        'entries' => [
            [
                'user_id' => $employee->id,
                'check_in' => '09:10',
                'check_out' => '17:00',
            ],
        ],
        ...$overrides,
    ];
}

test('admins can set check in and check out times for a chosen day', function () {
    $branch = Branch::factory()->create();
    $shift = Shift::factory()->create([
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'grace_period_minutes' => 15,
    ]);
    $admin = User::factory()->superAdmin()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'shift_id' => $shift->id,
    ]);

    $this->actingAs($admin)
        ->put(route('attendance.entries.sync'), recordPayload($employee, [
            'date' => '2026-08-20',
            'entries' => [[
                'user_id' => $employee->id,
                'check_in' => '09:10',
                'check_out' => '17:00',
            ]],
        ]))
        ->assertRedirect(route('attendance.index', ['date' => '2026-08-20']));

    $attendance = Attendance::query()->first();

    expect($attendance)->not->toBeNull()
        ->and($attendance->user_id)->toBe($employee->id)
        ->and($attendance->date->toDateString())->toBe('2026-08-20')
        ->and($attendance->check_in->format('H:i'))->toBe('09:10')
        ->and($attendance->check_out->format('H:i'))->toBe('17:00')
        ->and($attendance->status)->toBe(AttendanceStatus::Present)
        ->and((float) $attendance->work_hours)->toBe(7.83);
});

test('chosen late check in is marked late', function () {
    $branch = Branch::factory()->create();
    $shift = Shift::factory()->create([
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'grace_period_minutes' => 15,
    ]);
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'shift_id' => $shift->id,
    ]);

    $this->actingAs($admin)
        ->put(route('attendance.entries.sync'), recordPayload($employee, [
            'entries' => [[
                'user_id' => $employee->id,
                'check_in' => '09:40',
                'check_out' => '16:30',
            ]],
        ]))
        ->assertRedirect();

    $attendance = Attendance::query()->first();

    expect($attendance->status)->toBe(AttendanceStatus::LateAndEarlyLeave)
        ->and($attendance->late_minutes)->toBe(40)
        ->and($attendance->early_leave_minutes)->toBe(30);
});

test('admins can update times on an existing record', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->superAdmin()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in' => now()->setTime(8, 0),
        'check_out' => now()->setTime(12, 0),
    ]);

    $this->actingAs($admin)
        ->put(route('attendance.entries.sync'), recordPayload($employee, [
            'entries' => [[
                'user_id' => $employee->id,
                'check_in' => '10:00',
                'check_out' => '18:00',
            ]],
        ]))
        ->assertRedirect();

    expect(Attendance::query()->count())->toBe(1)
        ->and(Attendance::query()->first()->check_in->format('H:i'))->toBe('10:00')
        ->and(Attendance::query()->first()->check_out->format('H:i'))->toBe('18:00');
});

test('employees cannot record attendance times for other people', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $other = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($employee)
        ->put(route('attendance.entries.sync'), recordPayload($other))
        ->assertForbidden();
});

test('branch admins cannot record times for another branch', function () {
    $own = Branch::factory()->create();
    $other = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $own->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $other->id,
    ]);

    $this->actingAs($admin)
        ->put(route('attendance.entries.sync'), recordPayload($employee))
        ->assertSessionHasErrors('entries.0.user_id');

    expect(Attendance::query()->count())->toBe(0);
});

test('managers can record times for their department', function () {
    $branch = Branch::factory()->create();
    $department = Department::factory()->create(['branch_id' => $branch->id]);
    $manager = User::factory()->manager()->create([
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);

    $this->actingAs($manager)
        ->put(route('attendance.entries.sync'), recordPayload($employee))
        ->assertRedirect();

    expect(Attendance::query()->first()?->user_id)->toBe($employee->id);
});

test('the records page lists people and their times for the selected day', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'name' => 'Ziad Admin',
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'name' => 'Mona Fathy',
        'branch_id' => $branch->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-21',
        'check_in' => '2026-08-21 09:05:00',
        'check_out' => '2026-08-21 17:05:00',
    ]);

    $this->actingAs($admin)
        ->get(route('attendance.index', ['date' => '2026-08-21']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/Index')
            ->where('date', '2026-08-21')
            ->where('canRecord', true)
            ->has('people', 2)
            ->where('people.0.name', 'Mona Fathy')
            ->where('people.0.check_in', '09:05')
            ->where('people.0.check_out', '17:05'));
});

test('admins can download an excel sheet for the selected day', function () {
    $branch = Branch::factory()->create(['name' => 'Cairo HQ']);
    $admin = User::factory()->branchAdmin()->create([
        'name' => 'Ziad Admin',
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'name' => 'Mona Fathy',
        'branch_id' => $branch->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-21',
        'check_in' => '2026-08-21 09:05:00',
        'check_out' => '2026-08-21 17:05:00',
        'work_hours' => 8,
        'status' => AttendanceStatus::Present,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('attendance.export', ['date' => '2026-08-21']))
        ->assertOk()
        ->assertDownload('attendance-2026-08-21.xlsx');

    $sheet = excelSheetXml($response->streamedContent());

    expect($sheet)
        ->toContain('Mona Fathy')
        ->toContain('Cairo HQ')
        ->toContain('09:05')
        ->toContain('17:05');
});

test('admins can download an excel sheet for a chosen date range', function () {
    $branch = Branch::factory()->create(['name' => 'Cairo HQ']);
    $admin = User::factory()->branchAdmin()->create([
        'name' => 'Ziad Admin',
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'name' => 'Mona Fathy',
        'branch_id' => $branch->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-20',
        'check_in' => '2026-08-20 09:00:00',
        'check_out' => '2026-08-20 17:00:00',
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-22',
        'check_in' => '2026-08-22 09:30:00',
        'check_out' => '2026-08-22 16:45:00',
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-25',
        'check_in' => '2026-08-25 08:00:00',
        'check_out' => '2026-08-25 15:00:00',
    ]);

    $response = $this->actingAs($admin)
        ->get(route('attendance.export', [
            'from' => '2026-08-20',
            'to' => '2026-08-22',
        ]))
        ->assertOk()
        ->assertDownload('attendance-2026-08-20-to-2026-08-22.xlsx');

    $sheet = excelSheetXml($response->streamedContent());

    expect($sheet)
        ->toContain('2026-08-20')
        ->toContain('2026-08-22')
        ->toContain('09:00')
        ->toContain('09:30')
        ->not->toContain('2026-08-25');
});

test('employees only download their own attendance row', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'name' => 'Omar Said',
        'branch_id' => $branch->id,
    ]);
    $other = User::factory()->employee()->create([
        'name' => 'Other Staff',
        'branch_id' => $branch->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in' => now()->setTime(9, 0),
    ]);
    Attendance::factory()->create([
        'user_id' => $other->id,
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'check_in' => now()->setTime(9, 15),
    ]);

    $sheet = excelSheetXml(
        $this->actingAs($employee)
            ->get(route('attendance.export'))
            ->assertOk()
            ->streamedContent(),
    );

    expect($sheet)
        ->toContain('Omar Said')
        ->not->toContain('Other Staff');
});

test('admins can clear attendance records for a chosen day', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-21',
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-22',
    ]);

    $this->actingAs($admin)
        ->delete(route('attendance.records.clear'), ['date' => '2026-08-21'])
        ->assertRedirect(route('attendance.index', ['date' => '2026-08-21']));

    expect(Attendance::query()->count())->toBe(1)
        ->and(Attendance::query()->first()->date->toDateString())->toBe('2026-08-22');
});

test('admins can clear attendance records for a date range', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-20',
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-21',
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-25',
    ]);

    $this->actingAs($admin)
        ->delete(route('attendance.records.clear'), [
            'from' => '2026-08-20',
            'to' => '2026-08-21',
        ])
        ->assertRedirect(route('attendance.index', ['date' => '2026-08-20']));

    expect(Attendance::query()->count())->toBe(1)
        ->and(Attendance::query()->first()->date->toDateString())->toBe('2026-08-25');
});

test('branch admins cannot clear attendance records for another branch', function () {
    $own = Branch::factory()->create();
    $other = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $own->id,
    ]);
    $employee = User::factory()->employee()->create([
        'branch_id' => $other->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $other->id,
        'date' => '2026-08-21',
    ]);

    $this->actingAs($admin)
        ->delete(route('attendance.records.clear'), ['date' => '2026-08-21'])
        ->assertRedirect();

    expect(Attendance::query()->count())->toBe(1);
});

test('employees cannot clear attendance records', function () {
    $branch = Branch::factory()->create();
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
    ]);

    $this->actingAs($employee)
        ->delete(route('attendance.records.clear'), ['date' => now()->toDateString()])
        ->assertForbidden();

    expect(Attendance::query()->count())->toBe(1);
});

test('employees can view their attendance table across a date range', function () {
    $branch = Branch::factory()->create(['name' => 'Nasr City']);
    $employee = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);
    $other = User::factory()->employee()->create([
        'branch_id' => $branch->id,
    ]);

    $first = Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-10',
        'check_in' => '2026-08-10 09:00:00',
        'check_out' => '2026-08-10 17:00:00',
    ]);
    $second = Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-15',
        'check_in' => '2026-08-15 09:20:00',
        'check_out' => '2026-08-15 17:10:00',
    ]);
    Attendance::factory()->create([
        'user_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-07-20',
        'check_in' => '2026-07-20 09:00:00',
    ]);
    Attendance::factory()->create([
        'user_id' => $other->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-12',
        'check_in' => '2026-08-12 09:00:00',
    ]);

    $this->actingAs($employee)
        ->get(route('attendance.index', [
            'from' => '2026-08-01',
            'to' => '2026-08-31',
        ]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('attendance/Index')
            ->where('canRecord', false)
            ->where('from', '2026-08-01')
            ->where('to', '2026-08-31')
            ->has('attendances.data', 2)
            ->where('attendances.data.0.id', $second->id)
            ->where('attendances.data.1.id', $first->id)
            ->where('attendances.data.0.branch.name', 'Nasr City'));
});

function excelSheetXml(string $binary): string
{
    $path = tempnam(sys_get_temp_dir(), 'xlsx');
    file_put_contents($path, $binary);

    $zip = new ZipArchive;
    expect($zip->open($path))->toBeTrue();

    $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
    $zip->close();
    unlink($path);

    expect($sheet)->toBeString();

    return $sheet;
}
