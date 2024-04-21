<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
			'shipping_address' => $this->shipping_address,
			'user' => UserResource::make($this->whenLoaded('user')),
			'status' => $this->status,
			'total_amount' => $this->total_amount,
			'session_id' => $this->session_id,
            'created_at' => $this->created_at,
            'last_update' => $this->updated_at,
            'orderItems'=> OrderItemsResource::collection($this->whenLoaded('orderItems'))
        ];
    }
}
