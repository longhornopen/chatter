<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, int $int)
 * @method static find(mixed $course_user_id)
 * @method static firstOrCreate(array $array, array $array1)
 */
class CourseUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'email',
        'role',
        'lti_user_id',
    ];

    protected $casts = [
        'previous_mail_digest_at' => 'datetime',
        'last_launch_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
