<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentEndorsedChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $course_id;
    public $post_id;
    public $comment_id;
    public $endorsed;

    /**
     *
     * Create a new event instance.
     *
     * @param $course_id
     * @param $post_id
     * @param $comment_id
     * @param $endorsed
     */
    public function __construct($course_id, $post_id, $comment_id, $endorsed)
    {
        $this->course_id = $course_id;
        $this->post_id = $post_id;
        $this->comment_id = $comment_id;
        $this->endorsed = $endorsed;
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
