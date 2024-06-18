<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

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
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $productDistribution = Product::select('name', 'price')->get();
        $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
                            ->groupBy('month')
                            ->get();
        
        return view('dashboard', compact('cards', 'recentOrders', 'productDistribution', 'monthlySales'));
    }
}
