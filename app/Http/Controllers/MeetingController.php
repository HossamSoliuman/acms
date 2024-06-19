<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Http\Requests\UpdateMeetingRequest;
use App\Http\Resources\MeetingResource;
use App\Serveces\MeetingServece;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public $meetingService;
    public function __construct()
    {
        $this->meetingService = new MeetingServece;
    }

    public function index()
    {
        $meetings = Meeting::all();
        $meetings = MeetingResource::collection($meetings);
        return view('meetings', compact('meetings'));
    }

    public function store(Meeting $meeting)
    {
        $meeting->load(['eng', 'eng.engRates']);
        if ($meeting->status != Meeting::STATUS_ENG_INIT) {
            return $this->apiResponse(null, 'Already token', 0);
        }
        $payment_url = $this->meetingService->pay($meeting);
        return $this->apiResponse(['payment_url' => $payment_url]);
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
    public function checkoutSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        $response = $this->meetingService->checkoutSuccess($sessionId);
        return $response;
    }
}
