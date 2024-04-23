<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetAvailableTimeRequest;
use App\Http\Resources\EngAvailableTimesResource;
use App\Http\Resources\MettingResource;
use App\Models\Metting;
use Carbon\Carbon;
use Illuminate\Http\Request;


class EngController extends Controller
{
    public function setAvailableTime(SetAvailableTimeRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['eng_id'] = auth()->id();
        $checkIftimeExist = Metting::where('eng_id', auth()->id())->where('start_at', $validatedData['start_at'])->first();
        if ($checkIftimeExist) {
            return $this->apiResponse(null, 'This time already set', 0);
        }
        $metting = Metting::create($validatedData);

        return $this->apiResponse(EngAvailableTimesResource::make($metting));
    }
    public function getAvailableTimes()
    {
        $mettings = Metting::where('eng_id', auth()->id())->where('start_at', '>', Carbon::now())->get();
        return $this->apiResponse(EngAvailableTimesResource::collection($mettings));
    }
    public function getUpcomingMettings()
    {
        $mettings = Metting::with('user')
            ->where('eng_id', auth()->id())
            ->where('status', Metting::STATUS_USER_BOOK)
            ->where('start_at', '>', Carbon::now())
            ->get();
        return $this->apiResponse(MettingResource::collection($mettings));
    }
}
