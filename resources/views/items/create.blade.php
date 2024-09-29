@extends('layouts.app')

@section('title', 'Create New Item')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center animate-fade-in">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Create New Item</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('items.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="quantity">Initial Quantity</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 0) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Set the initial quantity for this item. You can adjust it later through restock or dispatch actions.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Item</button>
                            <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection