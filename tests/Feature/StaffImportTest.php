<?php

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Support\SimpleXlsx;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

test('admins can download a staff import template', function () {
    $admin = User::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->get(route('staff.import.template'))
        ->assertOk()
        ->assertDownload('staff-import.xlsx');
});

test('admins can import staff from an excel sheet', function () {
    $branch = Branch::factory()->create();
    $department = Department::factory()->create([
        'name' => 'Nursing',
        'branch_id' => $branch->id,
    ]);
    $admin = User::factory()->superAdmin()->create(['branch_id' => $branch->id]);
    $binary = app(SimpleXlsx::class)->binary(
        'Staff',
        ['name', 'email', 'phone', 'department'],
        [['Mona Fathy', 'mona.import@example.com', '01011111111', 'Nursing']],
    );
    $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'staff-import-'.uniqid().'.xlsx';
    file_put_contents($path, $binary);
    $file = new UploadedFile($path, 'staff.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

    $this->actingAs($admin)
        ->post(route('staff.import'), ['file' => $file])
        ->assertRedirect(route('staff.index'));

    $staff = User::query()->where('email', 'mona.import@example.com')->first();

    expect($staff)->not->toBeNull()
        ->and($staff->name)->toBe('Mona Fathy')
        ->and($staff->phone)->toBe('01011111111')
        ->and($staff->department_id)->toBe($department->id)
        ->and($staff->branch_id)->toBe($branch->id)
        ->and($staff->role?->slug)->toBe(UserRole::Employee->value)
        ->and($staff->must_change_password)->toBeTrue()
        ->and(Hash::check(User::DEFAULT_IMPORT_PASSWORD, $staff->password))->toBeTrue();
});

test('branch admins can import staff into their branch', function () {
    $branch = Branch::factory()->create();
    $department = Department::factory()->create([
        'name' => 'Reception',
        'branch_id' => $branch->id,
    ]);
    $admin = User::factory()->branchAdmin()->create([
        'branch_id' => $branch->id,
        'department_id' => $department->id,
    ]);
    $binary = app(SimpleXlsx::class)->binary(
        'Staff',
        ['name', 'email', 'phone', 'department'],
        [['Karim Adel', 'karim.import@example.com', '01022222222', 'Reception']],
    );
    $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'staff-import-'.uniqid().'.xlsx';
    file_put_contents($path, $binary);
    $file = new UploadedFile($path, 'staff.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

    $this->actingAs($admin)
        ->post(route('staff.import'), ['file' => $file])
        ->assertRedirect(route('staff.index'));

    $staff = User::query()->where('email', 'karim.import@example.com')->first();

    expect($staff)->not->toBeNull()
        ->and($staff->name)->toBe('Karim Adel')
        ->and($staff->branch_id)->toBe($branch->id)
        ->and($staff->department_id)->toBe($department->id)
        ->and($staff->must_change_password)->toBeTrue();
});

test('imported staff must change the default password after login', function () {
    $user = User::factory()->employee()->create([
        'password' => User::DEFAULT_IMPORT_PASSWORD,
        'must_change_password' => true,
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => User::DEFAULT_IMPORT_PASSWORD,
    ])->assertRedirect(route('security.edit'));

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('security.edit'));

    $this->actingAs($user)
        ->get(route('security.edit'))
        ->assertOk();

    $this->actingAs($user)
        ->from(route('security.edit'))
        ->put(route('user-password.update'), [
            'current_password' => User::DEFAULT_IMPORT_PASSWORD,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('security.edit'));

    expect($user->refresh()->must_change_password)->toBeFalse()
        ->and(Hash::check('new-password', $user->password))->toBeTrue();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

test('employees cannot import staff', function () {
    $employee = User::factory()->employee()->create();
    $file = UploadedFile::fake()->create('staff.csv', 10, 'text/csv');

    $this->actingAs($employee)
        ->post(route('staff.import'), ['file' => $file])
        ->assertForbidden();
});
