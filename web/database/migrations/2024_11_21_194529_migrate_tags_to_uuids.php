<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The 'courses' table has a 'post_tags' column, which looks like this:
        // [{"name":"announcement2","bgcolor":"#9A2617","teacher_only":true},{"name":"question","bgcolor":"#093145","teacher_only":false},{"name":"discussion","bgcolor":"#C2571A","teacher_only":false}]
        // Update these and add a 'uuid' attribute to every element of the array with a UUID, and save the result.
        DB::table('courses')->get()->each(function ($course) {
            $tags = collect(json_decode($course->post_tags));
            $tags = $tags->map(function ($tag) {
                $tag->uuid = Str::uuid();
                return $tag;
            });
            $course->post_tags = $tags->toJson();
            DB::table('courses')->where('id', $course->id)->update(['post_tags' => $course->post_tags]);
        });

        // The 'posts' table has a 'tag' column, with the name of the tag, and a 'course_id' pointing to the 'courses' table.
        // If 'tag' is non-empty, update it to contain the UUID of the tag instead of the name.
        DB::table('posts')->get()->each(function ($post) {
            if ($post->tag) {
                $course = DB::table('courses')->where('id', $post->course_id)->first();
                $tags = collect(json_decode($course->post_tags));
                $tag = $tags->firstWhere('name', $post->tag);
                $post->tag = $tag->uuid;
                DB::table('posts')->where('id', $post->id)->update(['tag' => $post->tag]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('posts')->get()->each(function ($post) {
            if ($post->tag) {
                $course = DB::table('courses')->where('id', $post->course_id)->first();
                $tags = collect(json_decode($course->post_tags));
                $tag = $tags->firstWhere('uuid', $post->tag);
                $post->tag = $tag->name;
                DB::table('posts')->where('id', $post->id)->update(['tag' => $post->tag]);
            }
        });

        DB::table('courses')->get()->each(function ($course) {
            $tags = collect(json_decode($course->post_tags));
            $tags = $tags->map(function ($tag) {
                unset($tag->uuid);
                return $tag;
            });
            $course->post_tags = $tags->toJson();
            DB::table('courses')->where('id', $course->id)->update(['post_tags' => $course->post_tags]);
        });
    }
};
