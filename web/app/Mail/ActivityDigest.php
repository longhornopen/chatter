<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ActivityDigest extends Mailable
{
    use Queueable, SerializesModels;

    public $course_user;
    public $posts;
    public $period_begin;
    public $period_end;
    public $unsub_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($course_user)
    {
        $this->course_user = $course_user;
        $this->period_begin = $this->course_user->previous_mail_digest_at;
        if ($this->period_begin === null) {
            $this->period_begin = Carbon::createFromDate(2020,1,1);
        }
        $this->period_end = new Carbon();
        $token = $course_user->createToken('unsubscribe', ['digest:unsubscribe'])->plainTextToken;
        $this->unsub_url = env('APP_URL') . '/course/' . $course_user->course_id
            . '/unsubscribe?user=' . $course_user->id . '&token=' . $token;
        $this->computePosts();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.activity_digest', [
            'course'=>$this->course_user->course,
            'posts'=>$this->posts,
            'unsubscribe_url'=>$this->unsub_url,
        ]);
    }

    public function hasContents() : bool
    {
        return count($this->posts) > 0;
    }

    protected function computePosts() : void
    {
        $period_begin = $this->period_begin;
        $period_end = $this->period_end;
        $posts = Post::where('course_id', $this->course_user->course_id)
            ->with([
                       'course_user_post_last_read_flags' => function ($query) {
                           $query->where('course_user_id', $this->course_user->id);
                       }
                   ])
            ->with([
                       'comments' => function ($query) use ($period_begin) {
                           $query->where('created_at', '>', $period_begin);
                       }
                   ])
            ->whereBetween('posts.last_comment_at', [$period_begin, $period_end])
            ->get()
            ->filter(function ($post) {
                $post_last_read = $post->course_user_post_last_read_flags->first() ?
                    $post->course_user_post_last_read_flags->first()->updated_at
                    : null;
                // in PHP8, can replace the above line with
                // $post_last_read = $post->course_user_post_last_read_flags->first()?->updated_at;
                return !$post_last_read || $post->last_comment_at > $post_last_read;
            })
            ->map(function ($post) use ($period_begin) {
                $post->is_new = $post->created_at > $period_begin;
                return $post;
            });
        $this->posts = $posts;
    }
}
