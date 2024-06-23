<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Http\Requests\UpdateMeetingRequest;
use App\Http\Resources\MeetingResource;
use App\Models\EngRates;
use App\Models\User;
use App\Serveces\MeetingServece;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function addReview(Request $request,)
    {
        $request->validate([
            'rate' => 'required|integer|min:1|max:5',
        ]);

        $meeting = Meeting::with(['user', 'eng.engRates'])->findOrFail($request->meeting_id);

        if (auth()->id() != $meeting->user_id) {
            return $this->apiResponse(null, 'Unauthorized action', 0, 401);
        }

        if ($meeting->status == Meeting::STATUS_REVIEW_SET) {
            return $this->apiResponse(null, 'Review have been set', 0, 400);
        }
        if ($meeting->status != Meeting::STATUS_MEETING_FINISHED) {
            return $this->apiResponse(null, 'Meeting not finished yet', 0, 400);
        }


        DB::transaction(function () use ($request, $meeting) {
            $meeting->update([
                'rating' => $request->rate,
                'status' => Meeting::STATUS_REVIEW_SET,
            ]);

            $eng_id = $meeting->eng_id;

            $meetings = Meeting::where('status', Meeting::STATUS_REVIEW_SET)
                ->where('eng_id', $eng_id)
                ->get();

            $totalRatings = $meetings->sum('rating');
            $numberOfRatings = $meetings->count();

            $overAllRating = $numberOfRatings ? $totalRatings / $numberOfRatings : 0;

            $engRates = EngRates::where('eng_id', $meeting->eng_id)->firstOrFail();
            $engRates->update([
                'overall_rating' => $overAllRating,
            ]);
        });

        return $this->apiResponse(MeetingResource::make($meeting));
    }
}
