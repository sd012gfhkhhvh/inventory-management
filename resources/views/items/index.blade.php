@extends('layouts.app')

@section('title', 'Inventory Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="row dashboard-row">
        <!-- Left Column: Heading and Inventory Details -->
        <div class="col-lg-3 pr-lg-2 dashboard-column left-column">
            <h1 class="h2 p-2 mb-5 text-primary font-weight-bold" style="background-color: #f8f9fa; border-radius: 5px;">
                Inventory Dashboard
            </h1>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-pie mr-2"></i>Inventory Summary
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Items</div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalItems }}</div>
                            </div>
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x text-primary-light"></i>
                                <i class="fas fa-clipboard-list fa-stack-1x fa-inverse"></i>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Quantity</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalQuantity }}</div>
                            </div>
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x text-success-light"></i>
                                <i class="fas fa-cubes fa-stack-1x fa-inverse"></i>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Low Stock Items</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockCount }}</div>
                            </div>
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x text-warning-light"></i>
                                <i class="fas fa-exclamation-triangle fa-stack-1x fa-inverse"></i>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Recent Transactions</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recentTransactions }}</div>
                            </div>
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x text-info-light"></i>
                                <i class="fas fa-exchange-alt fa-stack-1x fa-inverse"></i>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Middle Column: Search, Infographics, and Table -->
        <div class="col-lg-6 px-lg-2 dashboard-column middle-column">
            <!-- Search -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('items.index') }}" method="GET" id="searchForm">
                        <div class="input-group">
                            <input type="text" name="search" id="searchInput" class="form-control bg-light border-0 small" placeholder="Search for items..." value="{{ $search }}" autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Items Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Inventory Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ Str::limit($item->description, 50) }}</td>
                                        <td>
                                            <span class="badge badge-pill badge-{{ $item->quantity > 10 ? 'success' : ($item->quantity > 0 ? 'warning' : 'danger') }}">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#dispatchModal{{ $item->id }}" title="Dispatch" {{ $item->quantity == 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#restockModal{{ $item->id }}" title="Restock">
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>

            <!-- Infographics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Inventory Overview</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions and Low Stock Alerts -->
        <div class="col-lg-3 pl-lg-2 dashboard-column right-column">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('items.create') }}" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-plus fa-sm mr-2"></i>Add New Item
                    </a>
                    <a href="{{ route('items.export') }}" class="btn btn-success btn-block mb-3">
                        <i class="fas fa-file-export fa-sm mr-2"></i>Export Inventory
                    </a>
                    <a href="{{ route('reports.pdf') }}" class="btn btn-info btn-block">
                        <i class="fas fa-file-pdf fa-sm mr-2"></i>Download PDF Report
                    </a>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Low Stock Alerts</h6>
                </div>
                <div class="card-body p-0">
                    @if($lowStockItems->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($lowStockItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ route('items.show', $item) }}" class="text-decoration-none">
                                        {{ $item->name }}
                                    </a>
                                    <span class="badge badge-warning badge-pill">{{ $item->quantity }} left</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0 p-3">No low stock items.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Transaction History -->
            <!-- <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Recent Transaction History</h6>
                </div>
                <div class="card-body p-0">
                    <ul id="recentTransactionsList" class="list-group list-group-flush">
                        <li class="list-group-item text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> -->
        </div>
    </div>
</div>

@foreach($items as $item)
    <!-- Dispatch Modal -->
    <div class="modal fade" id="dispatchModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="dispatchModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dispatchModalLabel{{ $item->id }}">Dispatch {{ $item->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('items.dispatch', $item) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Current Quantity: {{ $item->quantity }}</p>
                        <div class="form-group">
                            <label for="dispatch_quantity{{ $item->id }}">Quantity to Dispatch</label>
                            <input type="number" class="form-control" id="dispatch_quantity{{ $item->id }}" name="quantity" min="1" max="{{ $item->quantity }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Dispatch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Restock Modal -->
    <div class="modal fade" id="restockModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="restockModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restockModalLabel{{ $item->id }}">Restock {{ $item->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('items.restock', $item) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Current Quantity: {{ $item->quantity }}</p>
                        <div class="form-group">
                            <label for="restock_quantity{{ $item->id }}">Quantity to Restock</label>
                            <input type="number" class="form-control" id="restock_quantity{{ $item->id }}" name="quantity" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Restock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Inventory Overview Chart
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Items', 'Total Quantity', 'Low Stock', 'Recent Transactions'],
            datasets: [{
                label: 'Inventory Overview',
                data: [{{ $totalItems }}, {{ $totalQuantity }}, {{ $lowStockCount }}, {{ $recentTransactions }}],
                backgroundColor: [
                    'rgba(78, 115, 223, 0.5)',
                    'rgba(28, 200, 138, 0.5)',
                    'rgba(246, 194, 62, 0.5)',
                    'rgba(54, 185, 204, 0.5)'
                ],
                borderColor: [
                    'rgba(78, 115, 223, 1)',
                    'rgba(28, 200, 138, 1)',
                    'rgba(246, 194, 62, 1)',
                    'rgba(54, 185, 204, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Recent Transaction History
    
</script>
@endsection