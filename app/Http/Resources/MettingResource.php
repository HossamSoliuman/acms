<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MettingResource extends JsonResource
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
            'id'=> $this->id,
			'start_at' => $this->start_at,
			'user' => UserResource::make($this->whenLoaded('user')),
			'url' => $this->url,
			'rating' => $this->rating,
			'status' => $this->status,
            'created_at' => $this->created_at,
            'last_update' => $this->updated_at,
        ];
    }
}
