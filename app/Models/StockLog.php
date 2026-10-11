<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'user_id',
        'type',
        'qty',
        'stock_before',
        'stock_after',
        'unit_cost',
        'reason',
    ];

    protected $casts = [
        'qty'          => 'integer',
        'stock_before' => 'integer',
        'stock_after'  => 'integer',
        'unit_cost'    => 'decimal:2',
    ];

    /* ─────────── Relationships ─────────── */

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /* ─────────── Helpers ─────────── */

    public function isIncrease(): bool
    {
        return $this->qty > 0;
    }

    public function isDecrease(): bool
    {
        return $this->qty < 0;
    }

    /* ─────────── Scopes ─────────── */

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}