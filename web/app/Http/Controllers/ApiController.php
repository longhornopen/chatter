<?php

/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection ReturnTypeCanBeDeclaredInspection */
/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Http\Controllers;

use App\Events\CommentAdded;
use App\Events\CommentEdited;
use App\Events\CommentEndorsedChanged;
use App\Events\CommentMutedChanged;
use App\Events\PostDeleted;
use App\Events\PostEdited;
use App\Events\PostLockedChanged;
use App\Events\PostPinnedChanged;
use App\Exceptions\LoginExpiredException;
use App\Exceptions\UnauthorizedException;
use App\Models\Comment;
use App\Models\CommentUpvote;
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
        return Course::where('id', $course_id)
            ->select(['id', 'name', 'close_date', 'post_tags'])
            ->firstOrFail();
    }

    public function postCourse(Request $request, $course_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkIsTeacher($course_user);

        $course = Course::findOrFail($course_id);
        if ($request->has('close_date')) {
            $course->close_date = $request->input('close_date');
        }
        if ($request->has('post_tags')) {
            $course->post_tags = $request->input('post_tags');
        }
        $course->save();
        return $course;
    }

    public function getCourseSummary(Request $request, $course_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);

        $course = Course::where('id',$course_user->course_id)
            ->select('id','name','close_date','post_tags')
            ->first();
        if (env('APP_HELP_URL')) {
            $course->help_url_text = env('APP_HELP_URL_TEXT', env('APP_HELP_URL'));
            $course->help_url = env('APP_HELP_URL');
        }

        $response = [];
        $response['course'] = $course;

        $upvoted_comment_ids = CommentUpvote::where('course_user_id', $course_user->course_id)
            ->pluck('comment_id');

        $response['user_upvoted_comment_ids'] = $upvoted_comment_ids;

        return $response;
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
            $fully_read_posts = DB::select(<<<'TAG'
SELECT posts.id as id, max(comments.created_at) as comment_date, max(flags.updated_at) as flag_date
from comments,posts,course_user_post_last_read_flags as flags
where comments.post_id = posts.id
  and flags.post_id = posts.id
  and posts.course_id = ?
  and flags.course_user_id = ?
group by posts.id
TAG
                , [$course_user->course_id,$course_user_id]);
            $fully_read_posts = array_filter($fully_read_posts, function($post) {
                return $post->flag_date >= $post->comment_date;
            });
            $post_ids = array_values(array_column($fully_read_posts, 'id'));
            $posts = $posts->whereNotIn('id', $post_ids);
        } elseif ($filter==='my_posts') {
            $posts = $posts->where('author_user_id', $course_user->id);
        }
        if ($search) {
            foreach (preg_split('/\s+/', $search) as $search_term) {
                if (str_starts_with($search_term, 'tag:')) {
                    $tag = explode('tag:', $search_term)[1];
                    $posts = $posts->where('tag', $tag);
                } else {
                    $posts = $posts->where(function ($query) use ($search_term) {
                        $query->where('title', 'like', '%' . $search_term . '%')
                            ->orWhere('body', 'like', '%' . $search_term . '%');
                    });
                }
            }
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
            $comment->num_upvotes = $comment->upvotes->count();
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
        $this->checkCourseCloseDate($course_id);

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
        if ($request->get('tag')) {
            $post_tags = Course::findOrFail($course_id)->post_tags;
            $post_tag = collect($post_tags)->first(function ($t) use ($request) {
                return $t['name'] === $request->get('tag');
            });
            if ($post_tag && $post_tag['teacher_only']) {
                $this->checkIsTeacher($course_user);
            }
        }
        $post->tag = $request->get('tag');
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
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkCourseCloseDate($course_id);

        $post = Post::findOrFail($post_id);
        if ($post->course_id !== (int)$course_user->course_id) {
            throw new UnauthorizedException("Unauthorized: Course ID mismatch.");
        }
        if ($post->author_user_id !== $course_user->id) {
            throw new UnauthorizedException("Unauthorized: Not your post");
        }
        $post->body = $request->json('body');
        if ($request->get('tag')) {
            $post_tags = Course::findOrFail($course_id)->post_tags;
            $post_tag = collect($post_tags)->first(function ($t) use ($request) {
                return $t['name'] === $request->get('tag');
            });
            if ($post_tag && $post_tag['teacher_only']) {
                $this->checkIsTeacher($course_user);
            }
        }
        $post->tag = $request->json('tag');
        $post->save();

        event(new PostEdited(
                  $course_user->course_id,
                  $post->id,
                  $post->body
              ));

        return "ok";
    }

    public function deletePost(Request $request, $course_id, $post_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkCourseCloseDate($course_id);
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
        $this->checkCourseCloseDate($course_id);
        $this->checkIsTeacher($course_user);

        $post = Post::findOrFail($post_id);
        $this->checkPostAuths($post, $course_user);
        $post->pinned = $pinned === 'true';
        $post->save();

        event(new PostPinnedChanged(
                  $course_user->course_id,
                  $post->id,
                  (bool)$post->pinned
              ));

        return $post;
    }

    public function lockPost(Request $request, $course_id, $post_id, $locked)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkCourseCloseDate($course_id);
        $this->checkIsTeacher($course_user);

        $post = Post::findOrFail($post_id);
        $this->checkPostAuths($post, $course_user);
        $post->locked = $locked==='true' ? 1 : 0;
        $post->save();

        event(new PostLockedChanged(
            $course_user->course_id,
            $post->id,
            (bool)$post->locked
              ));

        return $post;
    }

    public function createComment(Request $request, $course_id)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $post = Post::findOrFail($request->get('post_id'));
        $this->checkPostAuths($post, $course_user);
        $this->checkCourseCloseDate($course_id);

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
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkCourseCloseDate($course_id);

        $comment = Comment::findOrFail($comment_id);
        if ($comment->post->course_id !== (int)$course_user->course_id) {
            throw new UnauthorizedException("Unauthorized: Course ID mismatch.");
        }
        if ($comment->author_user_id !== $course_user->id) {
            throw new UnauthorizedException("Unauthorized: Not your comment");
        }
        $comment->body = $request->json('body');
        $comment->save();

        event(new CommentEdited(
                  $course_user->course_id,
                  $comment->id,
                  $comment->body
              ));

        return "ok";
    }

    public function endorseComment(Request $request, $course_id, $comment_id, $endorsed)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkCourseCloseDate($course_id);
        $this->checkIsTeacher($course_user);

        $comment = Comment::findOrFail($comment_id);
        $this->checkCommentAuths($comment, $course_user);

        $comment->endorsed = $endorsed==='true' ? 1 : 0;
        $comment->save();

        event(new CommentEndorsedChanged(
                  $course_user->course_id,
                  $comment->post_id,
                  $comment->id,
                  (bool)$comment->endorsed,
              ));

        return Comment::where('id', $comment->id)
            ->first();
    }

    public function muteComment(Request $request, $course_id, $comment_id, $muted)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkCourseCloseDate($course_id);
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

    public function upvoteComment(Request $request, $course_id, $comment_id, $upvoted)
    {
        $course_user = $this->getCourseUserFromSession($request, $course_id);
        $this->checkCourseCloseDate($course_id);
        $this->checkIsTeacher($course_user);

        $comment = Comment::findOrFail($comment_id);
        $this->checkCommentAuths($comment, $course_user);

        if ($upvoted==='true') {
            $upvote = new CommentUpvote();
            $upvote->course_user_id = $course_user->id;
            $upvote->comment_id = $comment_id;
            $upvote->save();
        } elseif ($upvoted==='false') {
            $upvotes = CommentUpvote::where('course_user_id', $course_user->id)
                ->where('comment_id', $comment_id)
                ->get();
            foreach ($upvotes as $upvote) {
                $upvote->delete();
            }
        }

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

    protected function checkCourseCloseDate($course_id) {
        $course = Course::findOrFail($course_id);
        if (!$course->close_date) {
            return;
        }
        if (Carbon::now()->greaterThan($course->close_date)) {
            throw new UnauthorizedException("Unauthorized: This course is closed.");
        }
    }

    protected function stripTags($html)
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    }

}
