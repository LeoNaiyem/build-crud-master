@extends('layouts.main')
@section('content')
    <h2>Edit Supplier</h2>
    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
        @include('suppliers._form', ['mode' => 'edit', 'supplier' => $supplier])
    </form>
@endsection