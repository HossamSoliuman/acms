@extends('layouts.admin')
@section('content')
    <div class="container ">
        <div class="row ">
            <div class="col-md-11">
                <h1>Orders</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th> Shipping Address</th>
                            <th> User</th>
                            <th> Total Amount</th>
                            <th> Status</th>
                            <th> Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr data-order-id="{{ $order->id }}">
                                <td class=" order-shipping_address">
                                    @foreach ($order->shipping_address as $key => $value)
                                        {{ ucfirst($key) }}: {{ $value }}
                                        <br>
                                    @endforeach

                                </td>
                                <td class=" order-user">{{ $order->user->name }}</td>
                                <td class=" order-user">{{ $order->total_amount }}</td>
                                <td class=" order-status">
                                    <span
                                        class="badge {{ $order->status === 'paid' ? 'bg-success' : 'bg-danger' }} text-white">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td class="order-user">{{ $order->created_at->diffForHumans() }}</td>

                                <td class="d-flex">
                                    <form action="{{ route('orders.show', ['order' => $order->id]) }}" method="get">
                                        @csrf
                                        <button type="submit" class=" ml-3 btn btn-primary">Order Details</button>
                                    </form>
                                    <form action="{{ route('orders.destroy', ['order' => $order->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class=" ml-3 btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    <script></script>
@endsection
