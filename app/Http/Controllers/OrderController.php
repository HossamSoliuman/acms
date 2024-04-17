<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use Hossam\Licht\Controllers\LichtBaseController;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends LichtBaseController
{

    public function index()
    {
        $orders = Order::with(['user'])->orderBy('created_at', 'desc')->paginate(10);
        $orders = OrderResource::collection($orders);
        return view('orders.index', compact('orders'));
    }

    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated());
        return redirect()->route('orders.index');
    }

    public function show(Order $order)
    {
        $order->load('orderItems', 'user');
        return view('orders.details', compact('order'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());
        return redirect()->route('orders.index');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index');
    }

    public function export(Order $order)
    {
        $pdf = Pdf::loadView('orders.export', compact('order'));

        return $pdf->download('order_details.pdf');
    }
}
