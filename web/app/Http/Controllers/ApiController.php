<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\CourseUserPostLastReadFlag;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getUserSelf(Request $request, $course_id) {
        $course_user_id = $request->attributes->get('course_user_id');
        $course_user = CourseUser::find($course_user_id);
        if ($course_user === null) {
            return response('Login expired.',401);
        }
        if ((string)$course_user->course_id !== $course_id) {
            return response('You are not authorized to access this resource.', 403);
        }

        return CourseUser::where('id',$course_user_id)
            ->select(['id','name','email','role'])
            ->first();
    }

    public function getCourseSummary(Request $request, $course_id)
    {
        $course_user_id = $request->attributes->get('course_user_id');
        $course_user = CourseUser::find($course_user_id);
        if ($course_user === null) {
            return response('Login expired.',401);
        }
        if ($course_user->course_id !== (int)$course_id) {
            return response('You are not authorized to access this resource.', 403);
        }

        $course = Course::where('id',$course_id)
            ->select('id','name')
            ->with('posts')
            ->first();
        $course->posts->map(function($post) use ($course_user_id) {
            $post->makeHidden('readComments');
            $post->num_comments = $post->comments->count();
            $post_last_read = CourseUserPostLastReadFlag::firstOrCreate([
                'post_id'=>$post->id,
                'course_user_id'=>$course_user_id
            ]);
            $post->num_unread_comments = $post->comments
                ->where('created_at', '>', $post_last_read->updated_at)
                ->count();
            return $post;
        });
        return $course;
    }

    public function getPost(Request $request, $course_id, $post_id) {
        $course_user_id = $request->attributes->get('course_user_id');
        $course_user = CourseUser::find($course_user_id);
        if ($course_user === null) {
            return response('Login expired.',401);
        }
        if ((string)$course_user->course_id !== $course_id) {
            return response('You are not authorized to access this resource.', 403);
        }

        $post = Post::where('id',$post_id)
            ->with(['comments'])
            ->first();
        if ($post === null) {
            return response('Post not found.', 404);
        }
        if ($post->course_id !== (int)$course_id) {
            return response('You are not authorized to access this resource.', 403);
        }

        $post_last_read = CourseUserPostLastReadFlag::firstOrCreate([
            'post_id'=>$post->id,
            'course_user_id'=>$course_user_id
        ]);
        $post_last_read->updated_at = new Carbon();
        $post_last_read->save();

        return $post;
    }
}
