<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'pinned' => $this->pinned,
            'created_at' => $this->created_at,
            'num_comments' => $this->comments->count(),
            'num_read_comments' => $this->readComments
                ->where('course_user_id',$request->attributes->get('course_user_id'))
                ->count(),
        ];
    }
}
