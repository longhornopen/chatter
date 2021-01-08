<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostLockedChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $course_id;
    public $post_id;
    public $locked;

    /**
     * Create a new event instance.
     *
     * @param $course_id
     * @param $post_id
     * @param $locked
     */
    public function __construct($course_id, $post_id, $locked)
    {
        $this->course_id = $course_id;
        $this->post_id = $post_id;
        $this->locked = $locked;
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
