<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'edited_at' => 'datetime',
    ];

    protected $appends = [
        'has_response_by_instructor'
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

    public function hasResponseByInstructor(): Attribute
    {
        return Attribute::make(
            get: function() {
                if ($this->author_user_role === 'teacher') {
                    // if a teacher made the post, they know about it
                    return true;
                }
                if ($this->comments()->where('author_user_role', 'teacher')->count() > 0) {
                    // if a teacher commented on the post, they know about it
                    return true;
                }
                if ($this->comments()->where('endorsed', true)->count() > 0) {
                    // if a teacher endorsed a comment in the post, they know about it
                    return true;
                }
                return false;
            }
        );

    }

    public function toArray(): array
    {
        if ($this->author_anonymous) {
            $this->makeHidden(['author_user_name']);
        }

        return parent::toArray();
    }
}
