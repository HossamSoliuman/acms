<?php

namespace App\Http\Controllers;

use App\Models\Metting;
use App\Http\Requests\StoreMettingRequest;
use App\Http\Requests\UpdateMettingRequest;
use App\Http\Resources\MettingResource;
use Carbon\Carbon;
use Hossam\Licht\Controllers\LichtBaseController;
use Illuminate\Support\Facades\Request;
use MacsiDigital\Zoom\Facades\Zoom;

class MettingController extends LichtBaseController
{

    public function index()
    {
        $mettings = Metting::all();
        $mettings = MettingResource::collection($mettings);
        return view('mettings', compact('mettings'));
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

    public function show(Metting $metting)
    {
        return $this->successResponse(MettingResource::make($metting));
    }

    public function update(UpdateMettingRequest $request, Metting $metting)
    {
        $metting->update($request->validated());
        return redirect()->route('mettings.index');
    }

    public function destroy(Metting $metting)
    {
        $metting->delete();
        return redirect()->route('mettings.index');
    }
}
