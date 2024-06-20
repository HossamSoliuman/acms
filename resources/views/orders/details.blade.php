@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Order Details</h3>
                            <a href="{{ route('orders.export', $order) }}" class="btn btn-light">Export</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4 font-weight-bold">Order ID:</div>
                            <div class="col-md-8">{{ $order->id }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 fw-bold">Date:</div>
                            <div class="col-md-8">
                                <div>{{ $order->created_at->format('F j, Y, g:i a') }}</div>
                                <div>{{ $order->created_at->diffForHumans() }}</div>
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
                            <div class="col-md-4 fw-bold">Shipping Address:</div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tbody>
                                        @foreach ($order->shipping_address as $key => $value)
                                            <tr>
                                                <td class="fw-bold">{{ ucwords(str_replace('_', ' ', $key)) }}:</td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="row mb-4">
                            <div class="col-md-4 font-weight-bold">Status:</div>
                            <div class="col-md-8">
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
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 fw-bold">Update Status:</div>
                            <div class="col-md-8">
                                <form action="{{ route('orders.update', $order) }}" method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="row form-group">
                                        <div class="col mt-1">
                                            <select name="status" class="form-control">
                                                <option value="in_delivery"
                                                    {{ $order->status == 'in_delivery' ? 'selected' : '' }}>In Delivery
                                                </option>
                                                <option value="received"
                                                    {{ $order->status == 'received' ? 'selected' : '' }}>
                                                    Received</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <button type="submit" class="btn btn-sm btn-dark ms-2">Update Status</button>
                                        </div>
                                    </div>
                                </form>
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
                                    <h5 class="mt-0 mb-2">{{ $item->product->name }}</h5>
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
