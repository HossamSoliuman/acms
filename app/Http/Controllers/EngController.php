<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetAvailableTimeRequest;
use App\Http\Resources\EngAvailableTimesResource;
use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;


class EngController extends Controller
{
    public function setAvailableTime(SetAvailableTimeRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['eng_id'] = auth()->id();
        $checkIftimeExist = Meeting::where('eng_id', auth()->id())->where('start_at', $validatedData['start_at'])->first();
        if ($checkIftimeExist) {
            return $this->apiResponse(null, 'This time already set', 0);
        }
        $meeting = Meeting::create($validatedData);

        return $this->apiResponse(EngAvailableTimesResource::make($meeting));
    }
    
    public function getAvailableTimes()
    {
        $meetings = Meeting::where('eng_id', auth()->id())->where('start_at', '>', Carbon::now())->get();

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

    public function getUpcomingMeetings()
    {
        $meetings = Meeting::with('user')
            ->where('eng_id', auth()->id())
            ->where('status', Meeting::STATUS_USER_BOOK)
            ->where('start_at', '>', Carbon::now())
            ->get();
        return $this->apiResponse(MeetingResource::collection($meetings));
    }
}
