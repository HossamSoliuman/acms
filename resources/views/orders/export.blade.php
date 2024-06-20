<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .order-details,
        .order-items {
            margin-bottom: 20px;
        }

        .order-details h4,
        .order-items h4 {
            margin-bottom: 10px;
        }

        .order-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .order-item img {
            max-width: 150px;
            margin-right: 20px;
        }

        .order-item div {
            flex: 1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }

        .bg-danger {
            background-color: #dc3545;
            color: white;
        }

        .bg-success {
            background-color: #28a745;
            color: white;
        }

        .bg-warning {
            background-color: #ffc107;
            color: black;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="order-details">
        <h4>Order ID: {{ $order->id }}</h4>
        <p>Date: {{ $order->created_at->format('F j, Y, g:i a') }}</p>
        <p>{{ $order->created_at->diffForHumans() }}</p>
        <p>User: {{ $order->user->name }} ({{ $order->user->email }})</p>
        <p>Shipping Address:</p>
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
        <p>Status:
            <span
                class="badge 
                {{ $order->status === 'unpaid' ? 'bg-danger' : '' }}
                {{ $order->status === 'paid' ? 'bg-success' : '' }}
                {{ $order->status === 'in_delivery' ? 'bg-warning' : '' }}
                {{ $order->status === 'received' ? 'bg-success' : '' }}">
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
        </p>
        <p>Total Amount: ${{ $order->total_amount }}</p>
    </div>

    <div class="order-items">
        <h4>Order Items:</h4>
        @foreach ($order->orderItems as $item)
            <div class="order-item">
                <img src="{{ $item->product->cover }}" alt="Product Image">
                <div>
                    <h5>{{ $item->product->name }}</h5>
                    <p><strong>Price:</strong> ${{ $item->product->price }}</p>
                    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>
