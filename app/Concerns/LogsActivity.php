<?php

namespace App\Concerns;

use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model): void {
            ActivityLogger::record('created', $model, $model->activitySnapshot());
        });

        static::updated(function (Model $model): void {
            $changes = $model->activityChanges();

            if ($changes === []) {
                return;
            }

            ActivityLogger::record('updated', $model, [
                ...$model->activitySnapshot(),
                'changes' => $changes,
            ]);
        });

        static::deleted(function (Model $model): void {
            ActivityLogger::record('deleted', $model, $model->activitySnapshot());
        });
    }

    /**
     * @return list<string>
     */
    protected function activityIgnored(): array
    {
        return [
            'password',
            'remember_token',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'two_factor_confirmed_at',
            'email_verified_at',
            'device_uuid',
            'updated_at',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function activitySnapshot(): array
    {
        return [
            'name' => $this->activityName(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function activityChanges(): array
    {
        return collect($this->getChanges())
            ->except($this->activityIgnored())
            ->except(['id'])
            ->all();
    }

    protected function activityName(): string
    {
        foreach (['name', 'title', 'email'] as $attribute) {
            $value = $this->getAttribute($attribute);

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return '#'.$this->getKey();
    }
}
