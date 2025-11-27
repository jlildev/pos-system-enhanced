<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class DraftOrder extends Model
{
    protected $fillable = [
        'user_id',
        'draft_number',
        'cart_items',
        'total',
        'is_active',
        'last_updated_at',
    ];

    protected $casts = [
        'cart_items' => 'array',
        'total' => 'decimal:2',
        'is_active' => 'boolean',
        'last_updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('last_updated_at', 'desc');
    }

    public static function generateDraftNumber(): string
    {
        $prefix = 'DFT-';
        $date = now()->format('Ymd');
        $lastDraft = self::where('draft_number', 'like', $prefix . $date . '%')
            ->latest('id')
            ->first();

        if (!$lastDraft) {
            $sequence = 1;
        } else {
            $lastSequence = (int) substr($lastDraft->draft_number, -4);
            $sequence = $lastSequence + 1;
        }

        return $prefix . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
