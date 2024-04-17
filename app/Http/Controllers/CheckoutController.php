<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function checkout(CheckoutRequest $request)
    {
        $shipping_address = $request->validated('shipping_address');
        $products = $request->validated('products');
        $user = auth()->user();
        $lineItems = [];
        $total_amount = 0;

        foreach ($products as $productData) {
            $product = Product::find($productData['id']);
            $lineItems[] = [
                'price_data' => [
                    'currency' => env('CASHIER_CURRENCY'),
                    'unit_amount' => $product->price * 100,
                    'product_data' => [
                        'name' => $product->name,
                    ],
                ],
                'quantity' => $productData['quantity'],
            ];
            $total_amount += $product->price * $productData['quantity'];
        }

        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'customer_email' => $user->email,
            'mode' => 'payment',
            'success_url' => route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
        ]);
        $order = Order::create([
            'shipping_address' => $shipping_address,
            'user_id' => $user->id,
            'status' => Order::STATU_UNPAID,
            'session_id' => $checkoutSession->id,
            'total_amount' => $total_amount,
        ]);
        foreach ($products as $productData) {
            $product = Product::find($productData['id']);
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
            ]);
        }
        return $this->apiResponse(['payment_url' => $checkoutSession->url]);
    }

    public function checkoutSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);

        if ($checkoutSession->payment_status == Order::STATU_PAID) {
            $order = Order::where('session_id', $sessionId)->first();
            $order->update([
                'status' => Order::STATU_PAID,
            ]);
            return redirect(env('APP_URL'));
        }
    }
}
