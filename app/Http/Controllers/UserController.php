<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSetMettingRequest;
use App\Http\Resources\MettingResource;
use App\Http\Resources\UserResource;
use App\Models\Metting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ZoomMeetingTrait;

class UserController extends Controller
{
    use ZoomMeetingTrait;
    public function orders()
    {
        $userId = auth()->user()->id;
        $user = User::with('orders', 'orders.orderItems', 'orders.orderItems.product')->orderByDesc('id')->whereId($userId)->first();
        return $this->apiResponse(UserResource::make($user));
    }
    public function getUpcomingMettings($user_id)
    {
        $mettings = Metting::where('user_id', $user_id)->where('status', Metting::STATUS_USER_BOOK)->get();
        return $this->apiResponse(MettingResource::collection($mettings));
    }
    public function setMetting(Metting $metting)
    {
        $metting->update([
            'user_id' => auth()->id(),
        ]);
        $data = null;
        $data['start_time'] = $metting->start_at;
        $response = $this->create($data);
        return $this->apiResponse($response);
    }
}
