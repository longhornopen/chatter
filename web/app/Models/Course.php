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

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
