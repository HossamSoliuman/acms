<?php

namespace App\Http\Controllers;

use App\Http\Resources\EngAvailableTimesResource;
use App\Http\Resources\EngResource;
use App\Http\Resources\MeetingResource;
use App\Http\Resources\UserResource;
use App\Models\Meeting;
use App\Models\User;
use App\Traits\ZoomMeetingTrait;
use Carbon\Carbon;

class UserController extends Controller
{
    use ZoomMeetingTrait;

    public function orders()
    {
        $userId = auth()->user()->id;
        $user = User::with('orders', 'orders.orderItems', 'orders.orderItems.product')->orderByDesc('id')->whereId($userId)->first();
        return $this->apiResponse(UserResource::make($user));
    }


    public function getUpcomingMeetings()
    {
        $meetings = Meeting::with(['eng','eng.engRates'])->where('user_id', auth()->id())->orderBy('id', 'desc')->get();
        return $this->apiResponse(MeetingResource::collection($meetings));
    }


    public function setMeeting(Meeting $meeting)
    {
        $meeting->update([
            'user_id' => auth()->id(),
        ]);
        $data = null;
        $data['start_time'] = $meeting->start_at;
        $response = $this->create($data);
        return $this->apiResponse($response);
    }


    public function getEngs()
    {
        $engs = User::with('engRates')->where('role', 'eng')->get();
        return $this->apiResponse(EngResource::collection($engs));
    }


    public function engAvailableTimes($user)
    {
        $meetings = Meeting::where('eng_id', $user)
            ->where('start_at', '>', Carbon::now())
            ->where('status', Meeting::STATUS_ENG_INIT)
            ->get();

        $availableTimes = [];

        foreach ($meetings as $meeting) {
            $date = Carbon::parse($meeting->start_at)->format('Y-m-d');
            $time = Carbon::parse($meeting->start_at)->format('H:i');

            if (!isset($availableTimes[$date])) {
                $availableTimes[$date] = [];
            }

            $availableTimes[$date][] = [
                'id' => $meeting->id,
                'time' => $time
            ];
        }
        return $this->apiResponse($availableTimes);
    }
}
