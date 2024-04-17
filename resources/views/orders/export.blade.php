<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <style>
        /* Define your CSS styles for the exported PDF */
        body {
            font-family: Arial, sans-serif;
        }

        /* Add any other styles as needed */
        .order-details {
            margin-bottom: 20px;
        }

        .order-details h4 {
            margin-bottom: 10px;
        }

        .order-item {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .order-item img {
            max-width: 150px;
            margin-right: 20px;
        }

        /* Add any other styles as needed */
    </style>
</head>

<body>
    <div class="order-details">
        <h4>Order ID: {{ $order->id }}</h4>
        <p>Date: {{ $order->created_at }}</p>
        <p>User: {{ $order->user->name }} ({{ $order->user->email }})</p>
        <p>Shipping Address:</p>
        <ul>
            @foreach ($order->shipping_address as $key => $value)
                <li>{{ ucfirst($key) }}: {{ $value }}</li>
            @endforeach
        </ul>
        <p>Status: <span
                class="badge {{ $order->status === 'paid' ? 'bg-success' : 'bg-danger' }} text-white">{{ ucfirst($order->status) }}</span>
        </p>
        <p>Total Amount: ${{ $order->total_amount }}</p>
    </div>

    <div class="order-items">
        <h4>Order Items:</h4>
        @foreach ($order->orderItems as $item)
            <div class="order-item">
                <img src="{{ asset($item->product->main_image) }}" alt="Product Image">
                <div>
                    <h5>{{ $item->name }}</h5>
                    <p><strong>Price:</strong> ${{ $item->price }}</p>
                    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>
