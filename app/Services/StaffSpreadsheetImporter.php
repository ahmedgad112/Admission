<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Support\SimpleXlsx;
use App\Support\SimpleXlsxReader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StaffSpreadsheetImporter
{
    public function __construct(
        public SimpleXlsx $xlsx,
        public SimpleXlsxReader $reader,
    ) {}

    public function template(): StreamedResponse
    {
        return $this->xlsx->download(
            'staff-import.xlsx',
            'Staff',
            $this->headers(),
            [['Ahmed Ali', 'ahmed.ali@example.com', '01000000000', 'Nursing']],
        );
    }

    /**
     * @return array{created: int, skipped: int, errors: list<string>}
     */
    public function import(User $actor, UploadedFile $file): array
    {
        $rows = $this->rows($file);
        $created = 0;
        $skipped = 0;
        $errors = [];
        $role = Role::requireBySlug(UserRole::Employee->value);
        $departments = $this->departmentsFor($actor);

        DB::transaction(function () use ($rows, $actor, $role, $departments, &$created, &$skipped, &$errors): void {
            foreach ($rows as $index => $row) {
                $line = $index + 2;
                $name = trim($row['name'] ?? '');
                $email = Str::lower(trim($row['email'] ?? ''));
                $phone = trim($row['phone'] ?? '');
                $departmentName = trim($row['department'] ?? '');

                if ($name === '' && $email === '') {
                    continue;
                }

                if ($name === '' || $email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $skipped++;
                    $errors[] = __('flash.staff.import_invalid', ['line' => $line]);

                    continue;
                }

                if (User::query()->where('email', $email)->exists()) {
                    $skipped++;
                    $errors[] = __('flash.staff.import_duplicate', ['line' => $line, 'email' => $email]);

                    continue;
                }

                $department = $this->matchDepartment($departments, $departmentName);

                if ($departmentName !== '' && $department === null) {
                    $skipped++;
                    $errors[] = __('flash.staff.import_department', ['line' => $line, 'department' => $departmentName]);

                    continue;
                }

                $branchId = $department instanceof Department
                    ? $department->branch_id
                    : $actor->branch_id;

                if ($branchId === null) {
                    $skipped++;
                    $errors[] = __('flash.staff.import_branch', ['line' => $line]);

                    continue;
                }

                User::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone !== '' ? $phone : null,
                    'password' => User::DEFAULT_IMPORT_PASSWORD,
                    'role_id' => $role->id,
                    'branch_id' => $branchId,
                    'department_id' => $department?->id,
                    'status' => UserStatus::Active,
                    'leave_days' => 21,
                    'permissions' => [],
                    'must_change_password' => true,
                    'email_verified_at' => now(),
                ]);

                $created++;
            }
        });

        return [
            'created' => $created,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * @return list<array{name: string, email: string, phone: string, department: string}>
     */
    private function rows(UploadedFile $file): array
    {
        $extension = Str::lower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        if (! is_string($path) || $path === '') {
            return [];
        }

        $raw = $extension === 'csv'
            ? $this->csvRows((string) file_get_contents($path))
            : $this->reader->rows($path);

        if ($raw === []) {
            return [];
        }

        $header = array_map(fn (string $value): string => $this->normalizeHeader($value), $raw[0]);
        $map = [
            'name' => $this->headerIndex($header, ['name', 'الاسم', 'اسم']),
            'email' => $this->headerIndex($header, ['email', 'e-mail', 'الايميل', 'الإيميل', 'ايميل']),
            'phone' => $this->headerIndex($header, ['phone', 'mobile', 'الهاتف', 'هاتف', 'الموبايل']),
            'department' => $this->headerIndex($header, ['department', 'القسم', 'قسم']),
        ];

        $rows = [];

        foreach (array_slice($raw, 1) as $line) {
            $rows[] = [
                'name' => $this->cell($line, $map['name']),
                'email' => $this->cell($line, $map['email']),
                'phone' => $this->cell($line, $map['phone']),
                'department' => $this->cell($line, $map['department']),
            ];
        }

        return $rows;
    }

    /**
     * @return list<list<string>>
     */
    private function csvRows(string $contents): array
    {
        $lines = preg_split('/\r\n|\n|\r/', $contents) ?: [];
        $rows = [];

        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }

            $rows[] = array_map(
                fn (?string $value): string => trim((string) $value, " \t\n\r\0\x0B\"'"),
                str_getcsv($line),
            );
        }

        return $rows;
    }

    /**
     * @return Collection<int, Department>
     */
    private function departmentsFor(User $actor)
    {
        return Department::query()
            ->visibleTo($actor)
            ->orderBy('name')
            ->get(['id', 'name', 'branch_id']);
    }

    /**
     * @param  Collection<int, Department>  $departments
     */
    private function matchDepartment($departments, string $name): ?Department
    {
        if ($name === '') {
            return null;
        }

        $needle = Str::lower($name);

        return $departments->first(
            fn (Department $department): bool => Str::lower($department->name) === $needle,
        );
    }

    /**
     * @param  list<string>  $header
     * @param  list<string>  $aliases
     */
    private function headerIndex(array $header, array $aliases): ?int
    {
        foreach ($header as $index => $value) {
            if (in_array($value, $aliases, true)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @param  list<string>  $row
     */
    private function cell(array $row, ?int $index): string
    {
        if ($index === null) {
            return '';
        }

        return trim((string) ($row[$index] ?? ''));
    }

    private function normalizeHeader(string $value): string
    {
        return Str::lower(trim($value));
    }

    /**
     * @return list<string>
     */
    private function headers(): array
    {
        return ['name', 'email', 'phone', 'department'];
    }
}
