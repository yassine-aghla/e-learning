<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'video' => $this->video,
            'cover' => $this->cover,
            'duration' => $this->duration,
            'level' => $this->level,
            'category_id' => $this->category_id,
            'status'=>$this->status,
            'category' => $this->category->name,
            'tags' => TagResource::collection($this->tags),
            'tags' => $this->tags->pluck('name'), 
        ];
    }
}