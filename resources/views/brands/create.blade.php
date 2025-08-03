@extends('layouts.app')

@section('content')
<div class="container text-end">
    <h2>إضافة براند</h2>

    <form action="{{ route('brands.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>اسم البراند</label>
            <input type="text" name="name" class="form-control" required>
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('brands.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
