<?php

namespace App\Serveces;

use App\Http\Resources\MeetingResource;
use Carbon\Carbon;
use App\Models\Meeting;
use App\Models\User;
use Stripe\Stripe;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use MacsiDigital\Zoom\Facades\Zoom;

class MeetingServece
{

    use ApiResponse;
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    public function pay($meeting)
    {
        $engAmount = $meeting->eng->engRates->meeting_rate;
        $systemFee = max($engAmount * 3 / 100, 3);
        $totalAmount = $engAmount + $systemFee;
        $payment_url = $this->checkout($totalAmount, $meeting);
        return $payment_url;
    }
    public function checkout($amount, $meeting)
    {
        $user = auth()->user();
        $lineItems = [];

        $lineItems[] = [
            'price_data' => [
                'currency' => env('CASHIER_CURRENCY'),
                'unit_amount' => $amount * 100,
                'product_data' => [
                    'name' => 'Taking a meeting',
                ],
            ],
            'quantity' => 1,

        ];

        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'customer_email' => $user->email,
            'mode' => 'payment',
            'success_url' => route('meetings.checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
        ]);
        $meeting->update([
            'session_id' => $checkoutSession->id,
            'user_id' => auth()->id()
        ]);

        return  $checkoutSession->url;
    }
    public function checkoutSuccess($sessionId)
    {
        $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
        if ($checkoutSession->payment_status == 'paid') {
            $meeting = Meeting::where('session_id', $sessionId)->first();
            return $this->makeZoomMeeting($meeting);
        }
    }
    public function makeZoomMeeting($meeting)
    {
        $engName = $meeting->eng->name;
        $userName = $meeting->user->name;
        $topic = 'Eng ' . $engName . ' meeting with ' . $userName;

        $zoomUser = Zoom::user()->first();
        $meetingData = [
            'duration' => 30,
            'topic' => $topic,
            'type' => 2,
            'start_time' => $meeting->start_at,
            'timezone' => 'Africa/Cairo'
        ];

        $zoomMeeting = Zoom::meeting()->make($meetingData);

        $zoomMeeting->settings()->make([
            'join_before_host' => true,
            'host_video' => true,
            'participant_video' => true,
            'mute_upon_entry' => false,
            'waiting_room' => false,
            'approval_type' => config('zoom.approval_type'),
            'audio' => config('zoom.audio'),
            'auto_recording' => config('zoom.auto_recording')
        ]);

        $zoomMeeting = $zoomUser->meetings()->save($zoomMeeting);

        $meeting->update([
            'url' => $zoomMeeting->join_url,
            'status' => Meeting::STATUS_USER_BOOK,
        ]);
        $this->addEngBudget($meeting->eng);
        return view('meetings-succuss', compact('meeting'));
    }

    public function addEngBudget($eng)
    {
        $eng = User::with('engRates')->where('id', $eng->id)->first();
        $engAmount = $eng->engRates->meeting_rate;
        $systemFee = max($engAmount * 10 / 100, 5);
        $netEngAmount = $engAmount - $systemFee;
        $eng->update([
            'balance' => $eng->balance + $netEngAmount,
        ]);
        return 1;
    }
}
