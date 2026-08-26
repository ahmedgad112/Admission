<?php

namespace App\Services;

use App\Enums\QrSessionType;
use App\Models\Branch;
use App\Models\QrSession;
use App\Models\User;
use Illuminate\Support\Str;

class QrSessionService
{
    public function currentOrCreate(Branch $branch, QrSessionType $type): QrSession
    {
        $existing = QrSession::query()
            ->where('branch_id', $branch->id)
            ->where('type', $type)
            ->where('expires_at', '>', now()->addSeconds(5))
            ->latest('id')
            ->first();

        if ($existing instanceof QrSession) {
            return $existing;
        }

        return $this->create($branch, $type);
    }

    public function expireActive(Branch $branch, QrSessionType $type): void
    {
        QrSession::query()
            ->where('branch_id', $branch->id)
            ->where('type', $type)
            ->where('expires_at', '>', now())
            ->update(['expires_at' => now()]);
    }

    public function create(Branch $branch, QrSessionType $type): QrSession
    {
        $this->expireActive($branch, $type);

        $token = Str::lower(Str::random(32));
        $expiresAt = now()->addSeconds((int) config('attendance.qr_ttl_seconds', 20));

        return QrSession::query()->create([
            'branch_id' => $branch->id,
            'token' => $token,
            'entry_code' => $this->uniqueEntryCode(),
            'signature' => $this->sign($branch->id, $type->value, $token, $expiresAt->getTimestamp()),
            'type' => $type,
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ]);
    }

    private function uniqueEntryCode(): string
    {
        $length = max(4, (int) config('attendance.entry_code_length', 6));

        do {
            $max = (10 ** $length) - 1;
            $code = str_pad((string) random_int(0, $max), $length, '0', STR_PAD_LEFT);
        } while (
            QrSession::query()
                ->where('entry_code', $code)
                ->where('expires_at', '>', now())
                ->exists()
        );

        return $code;
    }

    public function resolveAuthorizedBranch(User $user, ?int $branchId): Branch
    {
        if ($user->isSuperAdmin()) {
            $branch = $branchId
                ? Branch::query()->findOrFail($branchId)
                : Branch::query()->firstOrFail();

            return $branch;
        }

        abort_unless($user->canManageKiosk(), 403, 'You are not allowed to manage QR kiosks.');
        abort_unless($user->branch_id !== null, 422, 'Your account is not assigned to a branch.');

        if ($branchId !== null && $user->branch_id !== $branchId) {
            abort(403, 'You can only generate QR codes for your branch.');
        }

        return Branch::query()->findOrFail($user->branch_id);
    }

    public function findValid(string $token, QrSessionType $type): ?QrSession
    {
        $normalized = strtolower(preg_replace('/\s+/', '', $token) ?? '');

        $session = QrSession::query()
            ->with('branch')
            ->where('type', $type)
            ->where('expires_at', '>', now())
            ->where(function ($query) use ($normalized): void {
                $query->where('token', $normalized)
                    ->orWhere('entry_code', $normalized);
            })
            ->latest('id')
            ->first();

        if (! $session instanceof QrSession) {
            return null;
        }

        return $this->signatureIsValid($session) ? $session : null;
    }

    public function sign(int $branchId, string $type, string $token, int $expiresAt): string
    {
        return hash_hmac(
            'sha256',
            implode('|', [$branchId, $type, $token, (string) $expiresAt]),
            (string) config('app.key'),
        );
    }

    public function signatureIsValid(QrSession $session): bool
    {
        $expected = $this->sign(
            $session->branch_id,
            $session->type->value,
            $session->token,
            $session->expires_at->getTimestamp(),
        );

        return hash_equals($expected, $session->signature);
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(QrSession $session): array
    {
        return [
            'id' => $session->id,
            'branch_id' => $session->branch_id,
            'token' => $session->token,
            'entry_code' => $session->entry_code,
            'type' => $session->type->value,
            'expires_at' => $session->expires_at->toIso8601String(),
            'refresh_in_seconds' => max(1, (int) now()->diffInSeconds($session->expires_at, false)),
        ];
    }
}
