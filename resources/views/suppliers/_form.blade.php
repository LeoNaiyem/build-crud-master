@csrf
@if ($mode === 'edit')
    @method('PUT')
@endif

        <div class="mb-2">
            <label for="name">Name</label>
            <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" class="form-control">
        </div>
        <div class="mb-2">
            <label for="email">Email</label>
            <input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" class="form-control">
        </div>
        <div class="mb-2">
            <label for="phone">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" class="form-control">
        </div>
        <div class="mb-2">
            <label for="photo">Photo</label>
            @if(isset($supplier->photo) && $supplier->photo)
                <br>
                <img src="{{ asset('storage/' . $supplier->photo) }}" alt="Photo" width="100">
                <br>
            @endif
            <input type="file" name="photo" class="form-control">
        </div>
<button class="btn btn-success">{{ $mode === 'edit' ? 'Update' : 'Create' }}</button>