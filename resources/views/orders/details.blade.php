@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Order Details</h3>
                            <a href="{{ route('orders.export', $order) }}" class="btn btn-secondary">Export</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4 font-weight-bold">Order ID:</div>
                            <div class="col-md-8">{{ $order->id }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 font-weight-bold">Date:</div>
                            <div class="col-md-8">
                                <div>{{ $order->created_at }}</div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 font-weight-bold">User:</div>
                            <div class="col-md-8">
                                <div>{{ $order->user->name }}</div>
                                <div>{{ $order->user->email }}</div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 font-weight-bold">Shipping Address:</div>
                            <div class="col-md-8">
                                @foreach ($order->shipping_address as $key => $value)
                                    <div>{{ ucfirst($key) }}: {{ $value }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 font-weight-bold">Status:</div>
                            <div class="col-md-8">
                                <span
                                    class="badge {{ $order->status === 'paid' ? 'bg-success' : 'bg-danger' }} text-white">{{ ucfirst($order->status) }}</span>

                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 font-weight-bold">Total Amount:</div>
                            <div class="col-md-8">${{ $order->total_amount }}</div>
                        </div>
                        <hr>
                        <h5 class="mb-3">Order Items:</h5>
                        @foreach ($order->orderItems as $item)
                            <div class="media mb-4">
                                <img style="max-width: 150px" src="{{ asset($item->product->cover) }}" class="mr-3 rounded"
                                    alt="Product Image">
                                <div class="media-body">
                                    <h5 class="mt-0 mb-2">{{ $item->name }}</h5>
                                    <p class="mb-1"><strong>Price:</strong> ${{ $item->product->price }}</p>
                                    <p class="mb-0"><strong>Quantity:</strong> {{ $item->quantity }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
