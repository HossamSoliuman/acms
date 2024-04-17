<?php

namespace App\Http\Controllers;

use App\Models\OrderItems;
use App\Http\Requests\StoreOrderItemsRequest;
use App\Http\Requests\UpdateOrderItemsRequest;
use App\Http\Resources\OrderItemsResource;
use Hossam\Licht\Controllers\LichtBaseController;

class OrderItemsController extends LichtBaseController
{

    public function index()
    {
        $orderItems = OrderItems::all();
        $orderItems = OrderItemsResource::collection($orderItems);
        return view('orderItems', compact('orderItems'));
    }

    public function store(StoreOrderItemsRequest $request)
    {
        $orderItems = OrderItems::create($request->validated());
        return redirect()->route('order-items.index');
    }

    public function show(OrderItems $orderItems)
    {
        return $this->successResponse(OrderItemsResource::make($orderItems));
    }

    public function update(UpdateOrderItemsRequest $request, OrderItems $orderItems)
    {
        $orderItems->update($request->validated());
        return redirect()->route('order-items.index');
    }

    public function destroy(OrderItems $orderItems)
    {
        $orderItems->delete();
        return redirect()->route('order-items.index');
    }
}
