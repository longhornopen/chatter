<?php

namespace App\Http\Controllers;

use App\Exceptions\LoginExpiredException;
use App\Exceptions\UnauthorizedException;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\CourseUserPostLastReadFlag;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getUserSelf(Request $request)
    {
        $course_user = $this->getCourseUserFromSession($request);
        $course_user_id = $course_user->id;

        return CourseUser::where('id',$course_user_id)
            ->select(['id','course_id','name','email','role'])
            ->first();
    }

    public function getCourseSummary(Request $request)
    {
        $course_user = $this->getCourseUserFromSession($request);
        $course_user_id = $course_user->id;

        $course = Course::where('id',$course_user->course_id)
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

    public function getPost(Request $request, $post_id)
    {
        $course_user = $this->getCourseUserFromSession($request);
        $course_user_id = $course_user->id;

        $post = Post::where('id',$post_id)
            ->with(['comments'])
            ->first();
        if ($post === null) {
            return response('Post not found.', 404);
        }
        $this->checkPostAuths($post, $course_user);

        $post_last_read = CourseUserPostLastReadFlag::firstOrCreate([
            'post_id'=>$post->id,
            'course_user_id'=>$course_user_id
        ]);
        $post_last_read->updated_at = new Carbon();
        $post_last_read->save();

        return $post;
    }

    public function createPost(Request $request)
    {
//TODO
    }

    public function editPost(Request $request, $post_id)
    {
//TODO
    }

    public function deletePost(Request $request, $post_id)
    {
        $course_user = $this->getCourseUserFromSession($request);
        $this->checkIsTeacher($course_user);

        $post = Post::findOrFail($post_id);
        $this->checkPostAuths($post, $course_user);
        $post->delete();
        return "ok";
    }

    public function pinPost(Request $request, $post_id, $pinned)
    {
        $course_user = $this->getCourseUserFromSession($request);
        $this->checkIsTeacher($course_user);

        $post = Post::findOrFail($post_id);
        $this->checkPostAuths($post, $course_user);
        $post->pinned = $pinned === 'true';
        $post->save();
        return $post;
    }

    public function lockPost(Request $request, $post_id, $locked)
    {
        $course_user = $this->getCourseUserFromSession($request);
        $this->checkIsTeacher($course_user);

        $post = Post::findOrFail($post_id);
        $this->checkPostAuths($post, $course_user);
        $post->locked = $locked;
        $post->save();
        return $post;
    }

    public function createComment(Request $request)
    {
//TODO
    }

    public function editComment(Request $request, $comment_id)
    {
//TODO
    }

    public function endorseComment(Request $request, $comment_id, $endorsed)
    {
        $course_user = $this->getCourseUserFromSession($request);
        $this->checkIsTeacher($course_user);

        $comment = Comment::findOrFail($comment_id);
        $this->checkCommentAuths($comment, $course_user);

        if ($endorsed) {
            $comment->endorse
        } else {
            $comment->unendorse
        }
        $comment->save();
        return $comment;
    }

    public function muteComment(Request $request, $comment_id, $muted)
    {
        $course_user = $this->getCourseUserFromSession($request);
        $this->checkIsTeacher($course_user);

        $comment = Comment::findOrFail($comment_id);
        $this->checkCommentAuths($comment, $course_user);

        $comment->muted_by_user_id = $muted ? $course_user->id : null;
        $comment->save();
        return $comment;
    }

    /*
    // utility functions
    */

    protected function getCourseUserFromSession(Request $request)
    {
        $course_user_id = $request->attributes->get('course_user_id');
        $course_user = CourseUser::find($course_user_id);
        if ($course_user === null) {
            throw new LoginExpiredException("Login expired.");
        }
        return $course_user;
    }

    protected function checkPostAuths($post, $course_user)
    {
        if ($post->course_id !== (int)$course_user->course_id) {
            throw new UnauthorizedException("Unauthorized: Course ID mismatch.");
        }
    }

    protected function checkCommentAuths($comment, $course_user)
    {
        $post = Post::where('id',$comment->post_id)->firstOrFail();
        $this->checkPostAuths($post, $course_user);
    }

    protected function checkIsTeacher($course_user)
    {
        if ($course_user->role !== 'teacher') {
            throw new UnauthorizedException("Unauthorized: Only teachers may perform this action.");
        }
    }

}
