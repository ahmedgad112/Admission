<?php

namespace App\Http\Requests\Attendance;

use App\Enums\UserStatus;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SyncAttendanceEntriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('record', Attendance::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $entries = $this->input('entries');

        if (! is_array($entries)) {
            return;
        }

        $this->merge([
            'entries' => collect($entries)->map(function (mixed $entry): mixed {
                if (! is_array($entry)) {
                    return $entry;
                }

                return [
                    ...$entry,
                    'check_in' => $this->normalizeTime($entry['check_in'] ?? null),
                    'check_out' => $this->normalizeTime($entry['check_out'] ?? null),
                ];
            })->all(),
        ]);
    }

    private function normalizeTime(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        return substr($value, 0, 5);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'entries' => ['required', 'array'],
            'entries.*.user_id' => ['required', 'integer', 'distinct', 'exists:users,id'],
            'entries.*.check_in' => ['nullable', 'date_format:H:i'],
            'entries.*.check_out' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $actor = $this->user();

            if ($actor === null) {
                return;
            }

            $entries = $this->input('entries', []);

            if (! is_array($entries)) {
                return;
            }

            $userIds = collect($entries)->pluck('user_id')->filter()->map(fn ($id) => (int) $id)->all();
            $visibleIds = User::query()
                ->visibleTo($actor)
                ->withoutSuperAdmins()
                ->where('status', UserStatus::Active)
                ->whereNotNull('branch_id')
                ->whereIn('id', $userIds)
                ->pluck('id')
                ->all();

            foreach ($entries as $index => $entry) {
                if (! is_array($entry)) {
                    continue;
                }

                $userId = (int) ($entry['user_id'] ?? 0);
                $hasTime = filled($entry['check_in'] ?? null) || filled($entry['check_out'] ?? null);

                if (! $hasTime) {
                    continue;
                }

                if (! in_array($userId, $visibleIds, true)) {
                    $validator->errors()->add(
                        "entries.{$index}.user_id",
                        'You cannot record attendance for this person.',
                    );
                }

                if (blank($entry['check_in'] ?? null) && filled($entry['check_out'] ?? null)) {
                    $validator->errors()->add(
                        "entries.{$index}.check_in",
                        'Choose a check-in time before the check-out time.',
                    );
                }
            }
        });
    }
}
