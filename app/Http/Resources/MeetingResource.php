<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    private static $statusMapping = [
        'eng_init' => 'Eng Init',
        'user_book' => 'User Book',
        'meeting_finished' => 'Meeting Finished',
        'review_set' => 'Review Set',
    ];

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_at' => $this->start_at,
            'url' => $this->url,
            'rating' => $this->rating,
            'status' => $this->getFormattedStatus($this->status),
            'user' => UserResource::make($this->whenLoaded('user')),
            'eng' => UserResource::make($this->whenLoaded('eng')),
        ];
    }

    private function getFormattedStatus($status)
    {
        return self::$statusMapping[$status] ?? $status;
    }
}
