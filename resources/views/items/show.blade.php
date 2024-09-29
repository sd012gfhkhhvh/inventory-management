@extends('layouts.app')

@section('content')
<div class="container-fluid my-5">
    <div class="row">
        <!-- Item Details Card -->
        <div class="col-lg-8 col-md-6 mb-4">
            <div class="card2 shadow h-100">
                <div class="card2-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Item Details</h6>
                </div>
                <div class="card2-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Name:</strong> {{ $item->name }}</p>
                            <p><strong>Description:</strong> {{ $item->description }}</p>
                            <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                            <!-- Add more details as needed -->
                        </div>
                        <div class="col-md-4 text-center">
                            <div id="qrcode"></div>
                            <p class="mt-2"><small>Scan to view item</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card2 shadow h-100">
                <div class="card2-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Actions</h6>
                </div>
                <div class="card2-body">
                    <div class="d-flex flex-column h-100">
                        <a href="{{ route('items.edit', $item) }}" class="btn btn-warning btn-icon-split mb-3">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Edit Item</span>
                        </a>
                        <button type="button" class="btn btn-danger btn-icon-split mb-3" data-toggle="modal" data-target="#deleteModal">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Delete Item</span>
                        </button>
                        <button type="button" class="btn btn-primary btn-icon-split mb-3" data-toggle="modal" data-target="#dispatchModal">
                            <span class="icon text-white-50">
                                <i class="fas fa-paper-plane"></i>
                            </span>
                            <span class="text">Dispatch</span>
                        </button>
                        <button type="button" class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#restockModal">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Restock</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mb-3">Transaction History</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if($transaction->type == 'creation')
                                <span class="badge badge-info">Initial Creation</span>
                            @elseif($transaction->type == 'dispatch')
                                <span class="badge badge-warning">Dispatch</span>
                            @else
                                <span class="badge badge-success">Restock</span>
                            @endif
                        </td>
                        <td>
                            @if($transaction->type == 'dispatch')
                                <span class="text-danger">-{{ $transaction->quantity }}</span>
                            @else
                                <span class="text-success">+{{ $transaction->quantity }}</span>
                            @endif
                        </td>
                        <td>{{ $transaction->notes ?: 'No notes' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $transactions->links() }}
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('items.destroy', $item) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Dispatch Modal -->
<div class="modal fade" id="dispatchModal" tabindex="-1" role="dialog" aria-labelledby="dispatchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dispatchModalLabel">Dispatch {{ $item->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('items.dispatch', $item) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="quantity">Quantity to Dispatch</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="{{ $item->quantity }}" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Dispatch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div class="modal fade" id="restockModal" tabindex="-1" role="dialog" aria-labelledby="restockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restockModalLabel">Restock {{ $item->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('items.restock', $item) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="quantity">Quantity to Restock</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Restock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="qrcode"></div>

@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var typeNumber = 0;
        var errorCorrectionLevel = 'L';
        var qr = qrcode(typeNumber, errorCorrectionLevel);
        qr.addData('{{ route('items.show', $item) }}');
        qr.make();
        document.getElementById('qrcode').innerHTML = qr.createImgTag(5);
    });
</script>
@endsection