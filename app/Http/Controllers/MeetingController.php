<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeetingRequest;
use App\Models\Meeting;
use App\Http\Requests\UpdateMeetingRequest;
use App\Http\Resources\MeetingResource;
use Carbon\Carbon;
use Hossam\Licht\Controllers\LichtBaseController;
use Illuminate\Support\Facades\Request;
use MacsiDigital\Zoom\Facades\Zoom;

class MeetingController extends Controller
{

    public function index()
    {
        $meetings = Meeting::all();
        $meetings = MeetingResource::collection($meetings);
        return view('meetings', compact('meetings'));
    }

    public function store(Meeting $meeting)
    {
        $meeting->load('eng');
        $engName = $meeting->eng->name;
        $userName = auth()->user()->name;

        $topic = 'Eng ' . $engName . ' meeting with ' . $userName;

        $zoomUser = Zoom::user()->first();
        $meetingData = [
            'duration' => 30,
            'topic' => $topic,
            'type' => 2,
            'start_time' => $meeting->start_at,
            'timezone' => 'Africa/Cairo'
        ];

        $zoomMeeting = Zoom::meeting()->make($meetingData);

        $zoomMeeting->settings()->make([
            'join_before_host' => true,
            'host_video' => true,
            'participant_video' => true,
            'mute_upon_entry' => false,
            'waiting_room' => true,
            'approval_type' => config('zoom.approval_type'),
            'audio' => config('zoom.audio'),
            'auto_recording' => config('zoom.auto_recording')
        ]);

        $zoomMeeting = $zoomUser->meetings()->save($zoomMeeting);

        $meeting->update([
            'user_id' => auth()->id(),
            'url' => $zoomMeeting->join_url,
            'status' => Meeting::STATUS_USER_BOOK,
        ]);

        return $this->apiResponse(MeetingResource::make($meeting));
    }


    public function show(Meeting $meeting)
    {
        return $this->successResponse(MeetingResource::make($meeting));
    }

    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        $meeting->update($request->validated());
        return redirect()->route('meetings.index');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return redirect()->route('meetings.index');
    }
}
