@extends('layouts.main')
@section('content')
    <h2>Create Supplier</h2>
    <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
        @include('suppliers._form', ['mode' => 'create', 'supplier' => new App\Models\Supplier])
    </form>
@endsection