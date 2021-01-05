<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 * @method static where(string $string, $id)
 * @property int post_id
 * @property int course_user_id
 */
class CourseUserPostLastReadFlag extends Model
{
    protected $fillable = ['post_id', 'course_user_id'];

    use HasFactory;
}
