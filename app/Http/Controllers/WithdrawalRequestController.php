<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use App\Http\Requests\StoreWithdrawalRequestRequest;
use App\Http\Requests\UpdateWithdrawalRequestRequest;
use App\Http\Resources\WithdrawalRequestResource;
use App\Models\User;
use Illuminate\Pagination\Paginator;

class WithdrawalRequestController extends Controller
{

    public function index()
    {
        $statuses = [
            WithdrawalRequest::STATUS_PENDING,
            WithdrawalRequest::STATUS_VERIFIED,
            WithdrawalRequest::STATUS_SUCCEEDED,
            WithdrawalRequest::STATUS_FAILED,
            WithdrawalRequest::STATUS_CANCELED,
        ];

        $paginatedRequests = [];
        foreach ($statuses as $status) {
            Paginator::currentPageResolver(function () use ($status) {
                return request()->input($status . '_page', 1);
            });

            $paginatedRequests[$status] = WithdrawalRequest::where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], $status . '_page');
        }

        // Reset page resolver to default
        Paginator::currentPageResolver(function () {
            return request()->input('page', 1);
        });

        return view('withdrawalRequests', compact('paginatedRequests', 'statuses'));
    }


    public function store(StoreWithdrawalRequestRequest $request)
    {
        $validData = $request->validated();
        $userId = auth()->id();
        $validData['user_id'] = $userId;
        $balance = auth()->user()->balance;

        $pendingRequest = WithdrawalRequest::where('user_id', $userId)
            ->where('status', WithdrawalRequest::STATUS_PENDING)
            ->exists();

        if ($pendingRequest) {
            return $this->apiResponse(null, 'You already have a pending withdrawal request', 0, 400);
        }

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
