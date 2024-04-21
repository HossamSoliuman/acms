<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function orders()
    {
        $userId = auth()->user()->id;
        $user = User::with('orders', 'orders.orderItems', 'orders.orderItems.product')->orderByDesc('id')->whereId($userId)->first();
        return $this->apiResponse(UserResource::make($user));
    }
}
