<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function readers(): HasMany
    {
        return $this->hasMany(CommentReadUsers::class);
    }

    public function endorsers(): HasMany
    {
        return $this->hasMany(CommentEndorsedUsers::class);
    }
}
