<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static findOrFail($post_id)
 * @method static where(string $string, $post_id)
 * @property int course_id
 * @property int author_user_id
 * @property string author_user_name
 * @property string author_user_role
 * @property int author_anonymous
 * @property string title
 * @property string body
 * @property string tag
 * @property int id
 */
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'last_comment_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function course_user_post_last_read_flags(): HasMany
    {
        return $this->hasMany(CourseUserPostLastReadFlag::class);
    }
}
