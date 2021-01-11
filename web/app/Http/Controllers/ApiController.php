<?php

/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection ReturnTypeCanBeDeclaredInspection */
/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Http\Controllers;

use App\Events\CommentAdded;
use App\Events\CommentEndorsedChanged;
use App\Events\CommentMutedChanged;
use App\Events\PostDeleted;
use App\Events\PostLockedChanged;
use App\Events\PostPinnedChanged;
use App\Exceptions\LoginExpiredException;
use App\Exceptions\UnauthorizedException;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\CourseUserPostLastReadFlag;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getUserSelf(Request $request)
    {
        $course_user = $this->getCourseUserFromSession($request, null);
        $course_user_id = $course_user->id;

        return CourseUser::where('id',$course_user_id)
            ->select(['id','course_id','name','email','role'])
            ->first();
    }

    public function getUser(Request $request, $course_id, $user_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkIsTeacher($course_user);

        return CourseUser::where('course_id', $course_id)
            ->where('id', $user_id)
            ->firstOrFail();
    }

    public function getCourse(Request $request, $course_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);

        $course = Course::where('id',$course_user->course_id)
            ->select('id','name')
            ->first();
        if (env('APP_HELP_URL')) {
            $course->help_url_text = env('APP_HELP_URL_TEXT', env('APP_HELP_URL'));
            $course->help_url = env('APP_HELP_URL');
        }
        return $course;
    }

    public function getCoursePosts(Request $request, $course_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $course_user_id = $course_user->id;

        $filter = $request->get('filter');
        $search = $request->get('search');

        $posts = Post::where('course_id', $course_user->course_id)
            ->orderBy('created_at', 'desc');
        if ($filter==='pinned') {
            $posts = $posts->where('pinned', true);
        } elseif ($filter==='unread') {
            $post_ids = DB::select(<<<'TAG'
select distinct(posts.id) as id from posts
left join comments on posts.id=comments.post_id
left join course_user_post_last_read_flags on posts.id = course_user_post_last_read_flags.post_id
where
      posts.course_id = ?
  and posts.deleted_at is null
  and (course_user_post_last_read_flags.course_user_id = ?
        or course_user_post_last_read_flags.course_user_id is null)
  and (course_user_post_last_read_flags.updated_at is null
        or comments.created_at > course_user_post_last_read_flags.updated_at)
TAG
                , [$course_user->course_id,$course_user_id]);
            $post_ids = array_values(array_column($post_ids, 'id'));
            $posts = $posts->whereIn('id', $post_ids);
        } elseif ($filter==='my_posts') {
            $posts = $posts->where('author_user_id', $course_user->id);
        }
        if ($search) {
            $posts = $posts->where('title', 'like', '%'.$search.'%')
                ->orWhere('body', 'like', '%'.$search.'%');
        }
        $posts = $posts
            ->get()
            ->map(function($post) use ($course_user_id) {
                $post->makeHidden('readComments');
                $post->num_comments = $post->comments->count();
                $post_last_read = CourseUserPostLastReadFlag
                    ::where('post_id',$post->id)
                    ->where('course_user_id',$course_user_id)
                    ->first();
                $unread_comments = $post->comments;
                if ($post_last_read) {
                    $unread_comments = $unread_comments
                        ->where('created_at', '>', $post_last_read->updated_at);
                }
                $post->num_unread_comments = $unread_comments->count();
                if ($post->author_anonymous) {
                    $post->makeHidden(['author_user_name']);
                }
                return $post;
              });
        return $posts;
    }


    public function getPost(Request $request, $course_id, $post_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
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
        $post->comments = $post->comments->map(function($comment) use ($post_last_read) {
            $is_unread = $post_last_read->wasRecentlyCreated
                       || $comment->updated_at > $post_last_read->updated_at;
            $comment->is_unread = $is_unread;
            if ($comment->author_anonymous) {
                $comment->makeHidden(['author_user_name']);
            }
            return $comment;
        });
        $post_last_read->updated_at = new Carbon();
        $post_last_read->save();

        return $post;
    }

    public function createPost(Request $request, $course_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);

        $body = $request->get('body');
        $body = $this->stripTags($body);

        $post = new Post();
        $post->course_id = $course_user->course_id;
        $post->author_user_id = $course_user->id;
        $post->author_user_name = $course_user->name;
        $post->author_user_role = $course_user->role;
        $post->author_anonymous = $request->get('author_anonymous');
        $post->title = $request->get('title');
        $post->body = $body;
        $post->save();

        $flag = new CourseUserPostLastReadFlag();
        $flag->post_id = $post->id;
        $flag->course_user_id = $course_user->id;
        $flag->save();

        return Post::where('id',$post->id)
            ->with(['comments'])
            ->first();
    }

    public function editPost(Request $request, $course_id, $post_id)
    {
//TODO
    }

    public function deletePost(Request $request, $course_id, $post_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkIsTeacher($course_user);

        $post = Post::findOrFail($post_id);
        $this->checkPostAuths($post, $course_user);
        $post->delete();

        event(new PostDeleted(
                  $course_user->course_id,
                  $post->id,
              ));

        return "ok";
    }

    public function pinPost(Request $request, $course_id, $post_id, $pinned)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkIsTeacher($course_user);

        $post = Post::findOrFail($post_id);
        $this->checkPostAuths($post, $course_user);
        $post->pinned = $pinned === 'true';
        $post->save();

        event(new PostPinnedChanged(
                  $course_user->course_id,
                  $post->id,
                  $post->pinned ? true : false
              ));

        return $post;
    }

    public function lockPost(Request $request, $course_id, $post_id, $locked)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkIsTeacher($course_user);

        $post = Post::findOrFail($post_id);
        $this->checkPostAuths($post, $course_user);
        $post->locked = $locked==='true' ? 1 : 0;
        $post->save();

        event(new PostLockedChanged(
            $course_user->course_id,
            $post->id,
            $post->locked ? true : false
              ));

        return $post;
    }

    public function createComment(Request $request, $course_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $post = Post::findOrFail($request->get('post_id'));
        $this->checkPostAuths($post, $course_user);

        if ($post->locked) {
            throw new UnauthorizedException("You can't create a comment because that post has been locked.");
        }

        $body = $request->get('body');
        $body = $this->stripTags($body);

        $comment = new Comment();
        $comment->post_id = $request->get('post_id');
        $comment->author_user_id = $course_user->id;
        $comment->author_user_name = $course_user->name;
        $comment->author_user_role = $course_user->role;
        $comment->parent_comment_id = $request->get('parent_comment_id');
        $comment->author_anonymous = $request->get('author_anonymous');
        $comment->body = $body;
        $comment->save();

        event(new CommentAdded(
                  $course_user->course_id,
                  $comment->post_id,
                  $comment->id,
              ));

        return Comment::where('id', $comment->id)
            ->first();
    }

    public function editComment(Request $request, $course_id, $comment_id)
    {
//TODO
    }

    public function endorseComment(Request $request, $course_id, $comment_id, $endorsed)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkIsTeacher($course_user);

        $comment = Comment::findOrFail($comment_id);
        $this->checkCommentAuths($comment, $course_user);

        $comment->endorsed = $endorsed==='true' ? 1 : 0;
        $comment->save();

        event(new CommentEndorsedChanged(
                  $course_user->course_id,
                  $comment->post_id,
                  $comment->id,
                  $comment->endorsed ? true : false,
              ));

        return Comment::where('id', $comment->id)
            ->first();
    }

    public function muteComment(Request $request, $course_id, $comment_id, $muted)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkIsTeacher($course_user);

        $comment = Comment::findOrFail($comment_id);
        $this->checkCommentAuths($comment, $course_user);

        $comment->muted_by_user_id = $muted==='true' ? $course_user->id : null;
        $comment->save();

        event(new CommentMutedChanged(
                  $course_user->course_id,
                  $comment->post_id,
                  $comment->id,
                  $comment->muted_by_user_id,
              ));

        return Comment::where('id', $comment->id)
            ->first();
    }

    /*
    // utility functions
    */

    protected function getCourseUserFromSession(Request $request, $course_id)
    {
        $course_user_id = $request->attributes->get('course_user_id');
        $course_user = CourseUser::find($course_user_id);
        if ($course_user === null) {
            throw new LoginExpiredException("Login expired.");
        }
        if ($course_id!==null && $course_user->course_id != $course_id) {
            throw new LoginExpiredException("Login mismatch.");
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

    protected function stripTags($html)
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    }

}
