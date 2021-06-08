<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int course_user_id
 * @property int comment_id
 * @method static where(string $string, mixed $id)
 */
class CommentUpvote extends Model
{
    use HasFactory;
}
