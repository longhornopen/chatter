<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int post_id
 * @property int author_user_id
 * @property string author_user_name
 * @property string author_user_role
 * @property int parent_comment_id
 * @property boolean author_anonymous
 * @property string body
 * @method static findOrFail($comment_id)
 */
class Comment extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
