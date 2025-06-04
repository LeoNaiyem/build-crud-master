@extends('layouts.main')
@section('content')
<div class="container">
    <h1>Service List</h1>
    <a href="{{ route('services.create') }}" class="btn btn-primary mb-3">Create New</a>
    <table class="table table-bordered">
        <thead>
            <tr><th>id</th><th>name</th><th>description</th><th>price</th><th>created_at</th><th>updated_at</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @foreach ($services as $item)
            <tr><td>{{ $item->id }}</td><td>{{ $item->name }}</td><td>{{ $item->description }}</td><td>{{ $item->price }}</td><td>{{ $item->created_at }}</td><td>{{ $item->updated_at }}</td>
                <td>
                    <a href="{{ route('services.show', $item->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('services.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('services.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection