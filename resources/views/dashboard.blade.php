@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Dashboard Cards -->
        <div class="row">
            <!-- Cards for Users, Plants, Products, and Orders -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cards['users'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Plants</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cards['plants'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Products</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cards['products'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Orders</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cards['orders'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Repeat similar blocks for Plants, Products, and Orders -->
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Monthly Sales</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Distribution Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="productChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->total_amount }}</td>
                                            <td> <span
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
                        </td>
                        <td>{{ $order->created_at }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: @json($monthlySales->pluck('month')),
                datasets: [{
                    label: 'Sales',
                    data: @json($monthlySales->pluck('total')),
                    borderColor: 'rgba(78, 115, 223, 1)',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Sales'
                        }
                    }
                }
            }
        });

        // Product Distribution Chart
        const productCtx = document.getElementById('productChart').getContext('2d');
        const productChart = new Chart(productCtx, {
            type: 'pie',
            data: {
                labels: @json($productDistribution->pluck('name')),
                datasets: [{
                    data: @json($productDistribution->pluck('total_sales')),
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                }]
            }
        });
    </script>
@endsection
