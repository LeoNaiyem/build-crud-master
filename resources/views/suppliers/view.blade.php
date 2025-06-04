@extends('layouts.main')
@section('content')
    <h2>View Supplier</h2>
        <div class="mb-2">
            <strong>Id:</strong> {{ $supplier->id }}
        </div>
        <div class="mb-2">
            <strong>Name:</strong> {{ $supplier->name }}
        </div>
        <div class="mb-2">
            <strong>Email:</strong> {{ $supplier->email }}
        </div>
        <div class="mb-2">
            <strong>Phone:</strong> {{ $supplier->phone }}
        </div>
        <div class="mb-2">
            <strong>Photo:</strong><br>
            @if($supplier->photo)
                <img src="{{ asset('storage/' . $supplier->photo) }}" alt="Photo" width="150">
            @else
                No Photo
            @endif
        </div>
        <div class="mb-2">
            <strong>Created at:</strong> {{ $supplier->created_at }}
        </div>
        <div class="mb-2">
            <strong>Updated at:</strong> {{ $supplier->updated_at }}
        </div>

    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back</a>
@endsection