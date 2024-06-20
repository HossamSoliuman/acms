<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use Hossam\Licht\Controllers\LichtBaseController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;

class OrderController extends LichtBaseController
{


    public function index()
    {
        $statuses = [
            'paid',
            'in_delivery',
            'received',
            'unpaid'
        ];

        $paginatedOrders = [];
        foreach ($statuses as $status) {
            Paginator::currentPageResolver(function () use ($status) {
                return request()->input($status . '_page', 1);
            });

            $paginatedOrders[$status] = Order::where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], $status . '_page');
        }

        // Reset page resolver to default
        Paginator::currentPageResolver(function () {
            return request()->input('page', 1);
        });

        return view('orders.index', compact('paginatedOrders'));
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
        return redirect()->route('orders.show', $order);
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
