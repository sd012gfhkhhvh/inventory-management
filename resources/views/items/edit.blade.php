@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center animate-fade-in">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Edit Item: {{ $item->name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('items.update', $item) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $item->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $item->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="quantity">Current Quantity</label>
                            <input type="number" class="form-control" id="quantity" value="{{ $item->quantity }}" readonly>
                            <small class="form-text text-muted">Quantity can only be changed through restock or dispatch actions.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Item</button>
                            <a href="{{ route('items.show', $item) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add any specific scripts for the edit page here
</script>
@endsection