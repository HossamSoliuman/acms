<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Http\Requests\UpdateMeetingRequest;
use App\Http\Resources\MeetingResource;
use Carbon\Carbon;
use Hossam\Licht\Controllers\LichtBaseController;
use Illuminate\Support\Facades\Request;
use MacsiDigital\Zoom\Facades\Zoom;

class MeetingController extends LichtBaseController
{

    public function index()
    {
        $meetings = Meeting::all();
        $meetings = MeetingResource::collection($meetings);
        return view('meetings', compact('meetings'));
    }

    public function store(Request $request)
    {
        $user = Zoom::user()->first();

        $meetingData = [
            'duration' => 30,
            'topic' => 'New meeting',
            'type' => 2,
            'start_time' => new Carbon('2024-05-12 10:00:00'), // best to use a Carbon instance here.
            'timezone' => 'Africa/Cairo'


        ];
        $meeting = Zoom::meeting()->make($meetingData);

        $meeting->settings()->make([
            'join_before_host' => false,
            'host_video' => false,
            'participant_video' => false,
            'mute_upon_entry' => true,
            'waiting_room' => true,
            'approval_type' => config('zoom.approval_type'),
            'audio' => config('zoom.audio'),
            'auto_recording' => config('zoom.auto_recording')
        ]);
        return  $user->meetings()->save($meeting);
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
