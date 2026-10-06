<?php

use App\Models\AttendanceDayAdjustment;
use App\Models\Branch;
use App\Models\User;
use App\Services\StaffAttendanceSummary;
use App\Support\SimpleXlsx;
use Illuminate\Http\UploadedFile;

function leaveDaysSheet(array $headers, array $rows): UploadedFile
{
    $binary = app(SimpleXlsx::class)->binary('Leave days', $headers, $rows);
    $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'leave-days-'.uniqid().'.xlsx';
    file_put_contents($path, $binary);

    return new UploadedFile($path, 'leave-days.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
}

test('admins can add leave days to several staff at once', function () {
    $admin = User::factory()->superAdmin()->create();
    $first = User::factory()->employee()->create(['leave_days' => 10]);
    $second = User::factory()->employee()->create(['leave_days' => 12]);
    $untouched = User::factory()->employee()->create(['leave_days' => 8]);

    $this->actingAs($admin)
        ->post(route('staff.leave-days.bulk'), [
            'user_ids' => [$first->id, $second->id],
            'direction' => 'add',
            'days' => 3,
        ])
        ->assertRedirect(route('staff.index'))
        ->assertSessionHasNoErrors();

    expect($first->refresh()->leave_days)->toBe(10)
        ->and($second->refresh()->leave_days)->toBe(12)
        ->and($untouched->refresh()->leave_days)->toBe(8)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $first->id)->sum('days'))->toBe(3)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $second->id)->sum('days'))->toBe(3)
        ->and(app(StaffAttendanceSummary::class)->for($first, now()->format('Y-m'))['present_days'])->toBe(3)
        ->and(AttendanceDayAdjustment::query()->where('user_id', $untouched->id)->exists())->toBeFalse();
});

test('admins can deduct leave days from several staff at once', function () {
    $admin = User::factory()->superAdmin()->create();
    $first = User::factory()->employee()->create(['leave_days' => 10]);
    $second = User::factory()->employee()->create(['leave_days' => 6]);

    $this->actingAs($admin)
        ->post(route('staff.leave-days.bulk'), [
            'user_ids' => [$first->id, $second->id],
            'direction' => 'deduct',
            'days' => 2,
        ])
        ->assertRedirect(route('staff.index'))
        ->assertSessionHasNoErrors();

    expect($first->refresh()->leave_days)->toBe(10)
        ->and($second->refresh()->leave_days)->toBe(6)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $first->id)->sum('days'))->toBe(-2)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $second->id)->sum('days'))->toBe(-2);
});

test('bulk attendance changes are rejected when one person would pass the monthly limit', function () {
    $admin = User::factory()->superAdmin()->create();
    $first = User::factory()->employee()->create(['name' => 'Near Limit', 'leave_days' => 364]);
    $second = User::factory()->employee()->create(['leave_days' => 10]);

    $this->actingAs($admin)
        ->post(route('staff.leave-days.bulk'), [
            'user_ids' => [$first->id],
            'direction' => 'add',
            'days' => 31,
        ])
        ->assertSessionHasNoErrors();

    $this->actingAs($admin)
        ->from(route('staff.index'))
        ->post(route('staff.leave-days.bulk'), [
            'user_ids' => [$first->id, $second->id],
            'direction' => 'add',
            'days' => 1,
        ])
        ->assertRedirect(route('staff.index'))
        ->assertSessionHasErrors('days');

    expect($first->refresh()->leave_days)->toBe(364)
        ->and($second->refresh()->leave_days)->toBe(10)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $first->id)->sum('days'))->toBe(31)
        ->and(AttendanceDayAdjustment::query()->where('user_id', $second->id)->exists())->toBeFalse();
});

test('branch admins cannot bulk adjust staff outside their branch', function () {
    $branch = Branch::factory()->create();
    $admin = User::factory()->branchAdmin()->create(['branch_id' => $branch->id]);
    $local = User::factory()->employee()->create([
        'branch_id' => $branch->id,
        'leave_days' => 10,
    ]);
    $other = User::factory()->employee()->create(['leave_days' => 10]);

    $this->actingAs($admin)
        ->from(route('staff.index'))
        ->post(route('staff.leave-days.bulk'), [
            'user_ids' => [$local->id, $other->id],
            'direction' => 'add',
            'days' => 1,
        ])
        ->assertRedirect(route('staff.index'))
        ->assertSessionHasErrors('user_ids');

    expect($local->refresh()->leave_days)->toBe(10)
        ->and($other->refresh()->leave_days)->toBe(10);
});

test('employees cannot bulk adjust leave days', function () {
    $employee = User::factory()->employee()->create(['leave_days' => 10]);
    $coworker = User::factory()->employee()->create(['leave_days' => 10]);

    $this->actingAs($employee)
        ->post(route('staff.leave-days.bulk'), [
            'user_ids' => [$coworker->id],
            'direction' => 'add',
            'days' => 1,
        ])
        ->assertForbidden();

    expect($coworker->refresh()->leave_days)->toBe(10);
});

test('admins can download the leave days spreadsheet template', function () {
    $admin = User::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->get(route('staff.leave-days.template'))
        ->assertOk()
        ->assertDownload('leave-days.xlsx');
});

test('admins can add and deduct leave days from a spreadsheet', function () {
    $admin = User::factory()->superAdmin()->create();
    $ahmed = User::factory()->employee()->create([
        'name' => 'Ahmed Hassan',
        'email' => 'ahmed.days@example.com',
        'leave_days' => 10,
    ]);
    $sara = User::factory()->employee()->create([
        'name' => 'Sara Ali',
        'email' => 'sara.days@example.com',
        'leave_days' => 8,
    ]);

    $this->actingAs($admin)
        ->post(route('staff.leave-days.import'), [
            'file' => leaveDaysSheet(
                ['name', 'email', 'days', 'action'],
                [
                    ['Ahmed Hassan', 'ahmed.days@example.com', 3, 'add'],
                    ['Sara Ali', '', 2, 'خصم'],
                ],
            ),
        ])
        ->assertRedirect(route('staff.index'))
        ->assertSessionHasNoErrors();

    expect($ahmed->refresh()->leave_days)->toBe(10)
        ->and($sara->refresh()->leave_days)->toBe(8)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $ahmed->id)->sum('days'))->toBe(3)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $sara->id)->sum('days'))->toBe(-2);
});

test('a spreadsheet skips unknown names and does not change anyone else on that row', function () {
    $admin = User::factory()->superAdmin()->create();
    $staff = User::factory()->employee()->create([
        'name' => 'Mona Fathy',
        'leave_days' => 10,
    ]);

    $this->actingAs($admin)
        ->post(route('staff.leave-days.import'), [
            'file' => leaveDaysSheet(
                ['الاسم', 'الايميل', 'الايام', 'النوع'],
                [
                    ['شخص مش موجود', '', 2, 'زيادة'],
                    ['Mona Fathy', '', 1, 'إضافة'],
                ],
            ),
        ])
        ->assertRedirect(route('staff.index'));

    expect($staff->refresh()->leave_days)->toBe(10)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $staff->id)->sum('days'))->toBe(1);
});

test('a spreadsheet skips a name that matches more than one staff member', function () {
    $admin = User::factory()->superAdmin()->create();
    $first = User::factory()->employee()->create([
        'name' => 'Same Name',
        'email' => 'same.one@example.com',
        'leave_days' => 10,
    ]);
    $second = User::factory()->employee()->create([
        'name' => 'Same Name',
        'email' => 'same.two@example.com',
        'leave_days' => 10,
    ]);

    $this->actingAs($admin)
        ->post(route('staff.leave-days.import'), [
            'file' => leaveDaysSheet(
                ['name', 'days', 'action'],
                [['Same Name', 2, 'add']],
            ),
        ])
        ->assertRedirect(route('staff.index'));

    expect($first->refresh()->leave_days)->toBe(10)
        ->and($second->refresh()->leave_days)->toBe(10);

    $this->actingAs($admin)
        ->post(route('staff.leave-days.import'), [
            'file' => leaveDaysSheet(
                ['name', 'email', 'days', 'action'],
                [['Same Name', 'same.two@example.com', 2, 'add']],
            ),
        ])
        ->assertRedirect(route('staff.index'));

    expect($first->refresh()->leave_days)->toBe(10)
        ->and($second->refresh()->leave_days)->toBe(10)
        ->and((int) AttendanceDayAdjustment::query()->where('user_id', $second->id)->sum('days'))->toBe(2)
        ->and(AttendanceDayAdjustment::query()->where('user_id', $first->id)->exists())->toBeFalse();
});

test('employees cannot import leave days', function () {
    $employee = User::factory()->employee()->create(['leave_days' => 10]);

    $this->actingAs($employee)
        ->post(route('staff.leave-days.import'), [
            'file' => leaveDaysSheet(
                ['name', 'days', 'action'],
                [[$employee->name, 1, 'add']],
            ),
        ])
        ->assertForbidden();

    expect($employee->refresh()->leave_days)->toBe(10);
});
