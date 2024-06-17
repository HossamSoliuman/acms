<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use App\Http\Requests\StoreWithdrawalRequestRequest;
use App\Http\Requests\UpdateWithdrawalRequestRequest;
use App\Http\Resources\WithdrawalRequestResource;
use App\Models\User;

class WithdrawalRequestController extends Controller
{

    public function index()
    {
        $withdrawalRequests = WithdrawalRequest::all();
        $withdrawalRequests = WithdrawalRequestResource::collection($withdrawalRequests);
        $status = [
            WithdrawalRequest::STATUS_VERIFIED,
            WithdrawalRequest::STATUS_CANCELED,
            WithdrawalRequest::STATUS_FAILED,
            WithdrawalRequest::STATUS_SUCCEEDED
        ];
        return view('withdrawalRequests', compact('withdrawalRequests', 'status'));
    }

    public function store(StoreWithdrawalRequestRequest $request)
    {
        $validData = $request->validated();
        $validData['user_id'] = auth()->id();
        $balance = auth()->user()->balance;

        if ($validData['amount'] > $balance) {
            return $this->apiResponse(null, 'Insufficient balance', 0, 400);
        }

        $withdrawalRequest = WithdrawalRequest::create($validData);

        return $this->apiResponse($withdrawalRequest, 'Created Successfully');
    }


    public function show(WithdrawalRequest $withdrawalRequest)
    {
        return $this->successResponse(WithdrawalRequestResource::make($withdrawalRequest));
    }

    public function update(UpdateWithdrawalRequestRequest $request, WithdrawalRequest $withdrawalRequest)
    {
        $status = $request->validated('status');

        if ($status == WithdrawalRequest::STATUS_VERIFIED) {
            $user = $withdrawalRequest->user;
            if ($withdrawalRequest->amount > $user->balance) {
                $withdrawalRequest->update(['status' => WithdrawalRequest::STATUS_FAILED]);
            } else {
                $user->update([
                    'balance' => $user->balance - $withdrawalRequest->amount
                ]);
            }
        } else if ($status == WithdrawalRequest::STATUS_FAILED) {
            $user = $withdrawalRequest->user;
            $user->update([
                'balance' => $user->balance + $withdrawalRequest->amount
            ]);
        } else {
            $withdrawalRequest->update(['status' => $status]);
        }
        return redirect()->route('withdrawal-requests.index');
    }
    public function getWithdrawalRequests()
    {
        $requests = WithdrawalRequest::where('user_id', auth()->id())->get();
        return $this->apiResponse(WithdrawalRequestResource::collection($requests));
    }
    public function cancelWithdrawalRequest()
    {
        $withdrawalRequest = WithdrawalRequest::where('user_id', auth()->id())->whereStatus(WithdrawalRequest::STATUS_PENDING)->firstOrFail();
        $withdrawalRequest->update([
            'status' => WithdrawalRequest::STATUS_CANCELED
        ]);
        return $this->apiResponse(WithdrawalRequestResource::make($withdrawalRequest));
    }
}
