<?php

namespace Database\Factories;

use App\Enums\QrSessionType;
use App\Models\Branch;
use App\Models\QrSession;
use App\Services\QrSessionService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QrSession>
 */
class QrSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $token = Str::lower(Str::random(32));
        $branch = Branch::factory();
        $type = QrSessionType::CheckIn;
        $expiresAt = now()->addSeconds(20);

        return [
            'branch_id' => $branch,
            'token' => $token,
            'entry_code' => str_pad((string) fake()->unique()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT),
            'signature' => '',
            'type' => $type,
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (QrSession $session): void {
            if ($session->signature !== '') {
                return;
            }

            $service = app(QrSessionService::class);
            $session->signature = $service->sign(
                (int) $session->branch_id,
                $session->type->value,
                $session->token,
                $session->expires_at->getTimestamp(),
            );
        })->afterCreating(function (QrSession $session): void {
            if ($session->signature !== '') {
                return;
            }

            $service = app(QrSessionService::class);
            $session->forceFill([
                'signature' => $service->sign(
                    $session->branch_id,
                    $session->type->value,
                    $session->token,
                    $session->expires_at->getTimestamp(),
                ),
            ])->save();
        });
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subMinute(),
        ]);
    }
}
