@extends('layouts.main')
@section('content')
    <h2>Create Service</h2>
    <form action="{{ route('services.store') }}" method="POST">
        @include('services._form', ['mode' => 'create', 'service' => new App\Models\Service])
    </form>
@endsection