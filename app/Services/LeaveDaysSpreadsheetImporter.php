<?php

namespace App\Services;

use App\Models\User;
use App\Support\SimpleXlsx;
use App\Support\SimpleXlsxReader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeaveDaysSpreadsheetImporter
{
    public function __construct(
        public SimpleXlsx $xlsx,
        public SimpleXlsxReader $reader,
        public LeaveDaysAdjuster $adjuster,
    ) {}

    public function template(): StreamedResponse
    {
        return $this->xlsx->download(
            'leave-days.xlsx',
            'Leave days',
            $this->headers(),
            [
                ['Ahmed Ali', 'ahmed.ali@example.com', 2, 'add'],
                ['Sara Ali', 'sara.ali@example.com', 1, 'deduct'],
            ],
        );
    }

    /**
     * @return array{updated: int, skipped: int, errors: list<string>}
     */
    public function import(User $actor, UploadedFile $file): array
    {
        $rows = $this->rows($file);
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $people = User::query()
            ->visibleTo($actor)
            ->with('role')
            ->get()
            ->filter(fn (User $staff): bool => $actor->can('update', $staff))
            ->values();

        DB::transaction(function () use ($rows, $people, &$updated, &$skipped, &$errors): void {
            foreach ($rows as $index => $row) {
                $line = $index + 2;
                $name = trim($row['name']);
                $email = Str::lower(trim($row['email']));
                $days = $this->days($row['days']);
                $direction = $this->direction($row['action']);

                if ($name === '' && $email === '' && trim($row['days']) === '' && trim($row['action']) === '') {
                    continue;
                }

                if (($name === '' && $email === '') || $days === null || $direction === null) {
                    $skipped++;
                    $errors[] = __('flash.staff.leave_days_import_invalid', ['line' => $line]);

                    continue;
                }

                $staff = $this->match($people, $name, $email);

                if ($staff === null) {
                    $skipped++;
                    $errors[] = $email === '' && $this->named($people, $name)->count() > 1
                        ? __('flash.staff.leave_days_import_ambiguous', ['line' => $line, 'name' => $name])
                        : __('flash.staff.leave_days_import_missing', [
                            'line' => $line,
                            'name' => $email !== '' ? $email : $name,
                        ]);

                    continue;
                }

                if ($this->adjuster->balanceAfter($staff->leave_days, $direction, $days) === null) {
                    $skipped++;
                    $errors[] = __('flash.staff.leave_days_import_bounds', [
                        'line' => $line,
                        'name' => $staff->name,
                    ]);

                    continue;
                }

                $this->adjuster->apply($staff, $direction, $days);
                $updated++;
            }
        });

        return [
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * @param  Collection<int, User>  $people
     */
    private function match(Collection $people, string $name, string $email): ?User
    {
        if ($email !== '') {
            return $people->first(
                fn (User $staff): bool => Str::lower($staff->email) === $email,
            );
        }

        $matches = $this->named($people, $name);

        if ($matches->count() !== 1) {
            return null;
        }

        return $matches->first();
    }

    /**
     * @param  Collection<int, User>  $people
     * @return Collection<int, User>
     */
    private function named(Collection $people, string $name): Collection
    {
        $needle = Str::lower($name);

        return $people
            ->filter(fn (User $staff): bool => Str::lower($staff->name) === $needle)
            ->values();
    }

    private function days(string $value): ?int
    {
        $value = trim($value);

        if (preg_match('/^(\d+)(?:\.0+)?$/', $value, $matches) !== 1) {
            return null;
        }

        $days = (int) $matches[1];

        if ($days < 1 || $days > LeaveDaysAdjuster::MAX) {
            return null;
        }

        return $days;
    }

    private function direction(string $value): ?string
    {
        $value = Str::lower(trim($value));

        if (in_array($value, ['add', 'plus', '+', 'increase', 'زيادة', 'زياده', 'اضافه', 'إضافة', 'اضافة'], true)) {
            return 'add';
        }

        if (in_array($value, ['deduct', 'subtract', 'minus', '-', 'خصم', 'اخصم'], true)) {
            return 'deduct';
        }

        return null;
    }

    /**
     * @return list<array{name: string, email: string, days: string, action: string}>
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

        $header = array_map(fn (string $value): string => Str::lower(trim($value)), $raw[0]);
        $map = [
            'name' => $this->headerIndex($header, ['name', 'الاسم', 'اسم']),
            'email' => $this->headerIndex($header, ['email', 'e-mail', 'الايميل', 'الإيميل', 'ايميل']),
            'days' => $this->headerIndex($header, ['days', 'day', 'الأيام', 'الايام', 'عدد الايام', 'عدد الأيام', 'ايام']),
            'action' => $this->headerIndex($header, ['action', 'type', 'direction', 'النوع', 'الاجراء', 'الإجراء']),
        ];

        $rows = [];

        foreach (array_slice($raw, 1) as $line) {
            $rows[] = [
                'name' => $this->cell($line, $map['name']),
                'email' => $this->cell($line, $map['email']),
                'days' => $this->cell($line, $map['days']),
                'action' => $this->cell($line, $map['action']),
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

    /**
     * @return list<string>
     */
    private function headers(): array
    {
        return ['name', 'email', 'days', 'action'];
    }
}
