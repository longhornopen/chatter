<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail(string $course_id)
 * @method static where(string $string, $course_id)
 * @method static firstOrCreate(array $array, array $array1)
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lti_context_pk',
    ];

    protected $casts = [
        'close_date' => 'datetime',
        'post_tags' => 'array',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->post_tags = self::get_default_post_tags();
        });
    }

    /**
     * @throws \JsonException
     */
    public static function get_default_post_tags()
    {
        return json_decode(
            "[" .
            "{\"name\":\"announcement\",\"bgcolor\":\"#9A2617\",\"teacher_only\":true}," .
            "{\"name\":\"question\",\"bgcolor\":\"#093145\",\"teacher_only\":false}," .
            "{\"name\":\"discussion\",\"bgcolor\":\"#C2571A\",\"teacher_only\":false}" .
            "]",
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
