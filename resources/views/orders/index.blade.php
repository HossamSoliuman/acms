@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11">
                <h1>Orders</h1>

                @foreach ($paginatedOrders as $status => $orders)
                    <h1>
                        <span
                            class="badge 
                            {{ $status === 'unpaid' ? 'bg-danger' : '' }}
                            {{ $status === 'paid' ? 'bg-success' : '' }}
                            {{ $status === 'in_delivery' ? 'bg-warning text-dark' : '' }}
                            {{ $status === 'received' ? 'bg-success' : '' }} text-white">
                            @if ($status === 'unpaid')
                                Unpaid
                            @elseif($status === 'paid')
                                Paid
                            @elseif($status === 'in_delivery')
                                In Delivery
                            @elseif($status === 'received')
                                Received
                            @endif
                        </span>
                    </h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr data-order-id="{{ $order->id }}">
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->total_amount }}</td>
                                    <td>
                                        <span
                                            class="badge 
                                            {{ $order->status === 'unpaid' ? 'bg-danger' : '' }}
                                            {{ $order->status === 'paid' ? 'bg-success' : '' }}
                                            {{ $order->status === 'in_delivery' ? 'bg-warning text-dark' : '' }}
                                            {{ $order->status === 'received' ? 'bg-success' : '' }} text-white">
                                            @if ($order->status === 'unpaid')
                                                Unpaid
                                            @elseif($order->status === 'paid')
                                                Paid
                                            @elseif($order->status === 'in_delivery')
                                                In Delivery
                                            @elseif($order->status === 'received')
                                                Received
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <form action="{{ route('orders.show', ['order' => $order->id]) }}"
                                                method="get">
                                                @csrf
                                                <button type="submit" class="ml-3 btn btn-sm btn-light">Order
                                                    Details</button>
                                            </form>
                                            <form action="{{ route('orders.destroy', ['order' => $order->id]) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-3 btn-sm btn btn-dark">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $orders->appends([$status . '_page' => $orders->currentPage()])->links() }}
                    <!-- Paginate each section of orders -->
                @endforeach

            </div>
        </div>
    </div>
@endsection
