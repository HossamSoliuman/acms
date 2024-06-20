@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11">
                <h1>Orders</h1>

                @php
                    $statusClasses = [
                        'unpaid' => 'bg-danger',
                        'paid' => 'bg-success',
                        'in_delivery' => 'bg-warning text-dark',
                        'received' => 'bg-success'
                    ];

                    $statusLabels = [
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'in_delivery' => 'In Delivery',
                        'received' => 'Received'
                    ];
                @endphp

                @foreach ($paginatedOrders as $status => $orders)
                    <h1>
                        <span class="badge {{ $statusClasses[$status] }} text-white">
                            {{ $statusLabels[$status] }}
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
                                        <span class="badge {{ $statusClasses[$order->status] }} text-white">
                                            {{ $statusLabels[$order->status] }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <form action="{{ route('orders.show', ['order' => $order->id]) }}" method="get">
                                                @csrf
                                                <button type="submit" class="ml-3 btn btn-sm btn-light">Order Details</button>
                                            </form>
                                            {{-- <form action="{{ route('orders.destroy', ['order' => $order->id]) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-3 btn-sm btn btn-dark">Delete</button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $orders->appends([$status . '_page' => $orders->currentPage()])->links() }}
                @endforeach
            </div>
        </div>
    </div>
@endsection
