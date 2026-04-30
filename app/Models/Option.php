<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['poll_id', 'text', 'order'])]
class Option extends Model
{
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function votesCount(): int
    {
        return $this->votes()->count();
    }

    public function percentage(): float
    {
        $total = $this->poll->votesCount();

        if ($total === 0) {
            return 0.0;
        }

        return round(($this->votesCount() / $total) * 100, 2);
    }
}
