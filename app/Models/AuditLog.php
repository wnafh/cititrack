<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /* ─────────── Relationships ─────────── */

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /* ─────────── Helpers ─────────── */

    public function getChangedKeysAttribute(): array
    {
        $old = $this->old_values ?? [];
        $new = $this->new_values ?? [];

        return array_unique(array_merge(array_keys($old), array_keys($new)));
    }

    /* ─────────── Scopes ─────────── */

    public function scopeOfAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}