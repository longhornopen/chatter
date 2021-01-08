<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentMutedChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $course_id;
    public $post_id;
    public $comment_id;
    public $muted;

    /**
     * Create a new event instance.
     *
     * @param $course_id
     * @param $post_id
     * @param $comment_id
     * @param $muted
     */
    public function __construct($course_id, $post_id, $comment_id, $muted)
    {
        $this->course_id = $course_id;
        $this->post_id = $post_id;
        $this->comment_id = $comment_id;
        $this->muted = $muted;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('course.'.$this->course_id);
    }
}
