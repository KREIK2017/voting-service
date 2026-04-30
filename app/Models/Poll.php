<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'title',
    'description',
    'is_active',
    'starts_at',
    'ends_at',
    'allow_multiple',
])]
class Poll extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'allow_multiple' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->orderBy('order');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function isActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }

    public function hasVoted(User $user): bool
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function votesCount(): int
    {
        return $this->votes()->count();
    }
}
