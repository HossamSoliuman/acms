<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    /**
     * create transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTransaction()
    {
        return view('transaction');
    }
    /**
     * process transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function processTransaction(Request $request)
    {
        $shipping_address = $request->validated('shipping_address');
        $products = $request->validated('products');
        $user = auth()->user();
        $lineItems = [];
        $total_amount = 0;

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "1000.00"
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return $this->apiResponse([
                        'redirect_url' => $links['href']
                    ]);
                }
            }
            return $this->apiResponse(null, 'Something went wrong.', 0, 400);
        } else {

            $message = $response['message'] ?? 'Something went wrong.';
            return $this->apiResponse(null, $message, 0, 400);
        }
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
    }
    /**
     * success transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function successTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()
                ->url(env('APP_URL'));
        } else {
            return redirect()
                ->url(env('APP_URL'));
        }
    }
    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request)
    {
        return redirect()
            ->url(env('APP_URL'));
    }
}
