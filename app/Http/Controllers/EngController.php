<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetAvailableTimeRequest;
use App\Http\Resources\MettingResource;
use App\Models\Metting;
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
        return $this->apiResponse(MettingResource::make($metting));
    }
    public function getAvailableTimes($eng_id)
    {
        $mettings = Metting::where('eng_id', $eng_id)->get();
        return $this->apiResponse(MettingResource::collection($mettings));
    }
    public function getUpcomingMettings($eng_id)
    {
        $mettings = Metting::where('eng_id', $eng_id)->where('status', Metting::STATUS_USER_BOOK)->get();
        return $this->apiResponse(MettingResource::collection($mettings));
    }
}
