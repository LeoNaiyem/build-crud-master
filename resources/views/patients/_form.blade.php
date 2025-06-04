@extends('layouts.main')
@section('content')
    <h2>{{$mode === 'edit' ? "Update" : "Create"}} Patient</h2>
    <form action="{{ $mode === 'edit' ? route('patients.update', $patient) : route('patients.store') }}" method="POST">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif
        <div class="mb-2">
            <label for="name">Name</label>
            <input type="text" name="name" value="{{ old('name', $patient->name ?? '') }}" class="form-control">
        </div>
        <div class="mb-2">
            <label for="mobile">Mobile</label>
            <input type="text" name="mobile" value="{{ old('mobile', $patient->mobile ?? '') }}" class="form-control">
        </div>
        <div class="mb-2">
            <label for="dob">Date Of Birth</label>
            <input type="date" name="dob" value="{{ old('dob', $patient->dob ?? '') }}" class="form-control">
        </div>
        <button class="btn btn-success">{{$mode === 'edit' ? 'Update' : 'Create'}}</button>
    </form>
@endsection