@extends('layouts.main')
@section('content')
    <h2>View Service</h2>
        <div class="mb-2">
            <strong>Id:</strong> {{ $service->id }}
        </div>
        <div class="mb-2">
            <strong>Name:</strong> {{ $service->name }}
        </div>
        <div class="mb-2">
            <strong>Description:</strong> {{ $service->description }}
        </div>
        <div class="mb-2">
            <strong>Price:</strong> {{ $service->price }}
        </div>
        <div class="mb-2">
            <strong>Created at:</strong> {{ $service->created_at }}
        </div>
        <div class="mb-2">
            <strong>Updated at:</strong> {{ $service->updated_at }}
        </div>

    <a href="{{ route('services.index') }}" class="btn btn-secondary">Back</a>
@endsection