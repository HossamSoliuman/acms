<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_at' => $this->start_at,
            'url' => $this->url,
            'rating' => $this->rating,
            'status' => $this->status,
            'user' => UserResource::make($this->whenLoaded('user')),
            'eng' => UserResource::make($this->whenLoaded('eng')),
        ];
    }
}
