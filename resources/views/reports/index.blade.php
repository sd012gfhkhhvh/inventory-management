@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="mb-4">Inventory Reports</h1>
    <div class="row">
        <div class="col-md-6">
            <canvas id="inventoryChart"></canvas>
        </div>
        <div class="col-md-6">
            <h3>Summary</h3>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Total Items
                    <span class="badge bg-primary rounded-pill">{{ $totalItems }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Total Quantity
                    <span class="badge bg-success rounded-pill">{{ $totalQuantity }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Low Stock Items
                    <span class="badge bg-warning rounded-pill">{{ $lowStockItems }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Recent Transactions
                    <span class="badge bg-info rounded-pill">{{ $recentTransactions }}</span>
                </li>
            </ul>
            <a href="{{ route('reports.pdf') }}" class="btn btn-primary mt-3">Download PDF Report</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Add Chart.js code here to create the inventory chart
    // This is just a placeholder, you'll need to implement the actual chart
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Items', 'Total Quantity', 'Low Stock', 'Recent Transactions'],
            datasets: [{
                label: 'Inventory Overview',
                data: [{{ $totalItems }}, {{ $totalQuantity }}, {{ $lowStockItems }}, {{ $recentTransactions }}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
