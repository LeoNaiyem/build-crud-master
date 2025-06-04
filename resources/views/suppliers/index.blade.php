@extends('layouts.main')
@section('content')
<div class="container">
    <h1>Supplier List</h1>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">Create New</a>
    <table class="table table-bordered">
        <thead>
            <tr><th>Id</th><th>Name</th><th>Email</th><th>Phone</th><th>Photo</th><th>Created at</th><th>Updated at</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @foreach ($suppliers as $item)
            <tr><td>{{ $item->id }}</td><td>{{ $item->name }}</td><td>{{ $item->email }}</td><td>{{ $item->phone }}</td><td>@if($item->photo)<img src="{{ asset('storage/' . $item->photo) }}" alt="photo" width="50">@endif</td><td>{{ $item->created_at }}</td><td>{{ $item->updated_at }}</td><td>
        <a href="{{ route('suppliers.show', $item->id) }}" class="btn btn-sm btn-info">View</a>
        <a href="{{ route('suppliers.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('suppliers.destroy', $item->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection