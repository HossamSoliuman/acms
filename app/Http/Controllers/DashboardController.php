<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cards = [];
        $cards['users'] = User::count();
        $cards['plants'] = Plant::count();
        $cards['products'] = Product::count();
        $cards['orders'] = Order::count();

        // Additional data for charts and tables
        $recentOrders = Order::with('user')->orderBy('updated_at', 'desc')->take(5)->get();

        // Fetch product sales distribution
        $productDistribution = Product::select('products.name', DB::raw('SUM(order_items.quantity * products.price) as total_sales'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.name')
            ->get();

        $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->get();

        return view('dashboard', compact('cards', 'recentOrders', 'productDistribution', 'monthlySales'));
    }
}
