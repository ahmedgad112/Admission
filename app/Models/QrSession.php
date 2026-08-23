<?php

namespace App\Models;

use App\Enums\QrSessionType;
use Database\Factories\QrSessionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $branch_id
 * @property string $token
 * @property string|null $entry_code
 * @property string $signature
 * @property QrSessionType $type
 * @property Carbon $expires_at
 * @property Carbon $created_at
 */
#[Fillable(['branch_id', 'token', 'entry_code', 'signature', 'type', 'expires_at', 'created_at'])]
class QrSession extends Model
{
    /** @use HasFactory<QrSessionFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => QrSessionType::class,
            'expires_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
