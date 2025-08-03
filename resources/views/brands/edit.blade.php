@extends('layouts.app')

@section('content')
<div class="container text-end">
    <h2>تعديل البراند</h2>

    <form action="{{ route('brands.update', $brand->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>اسم البراند</label>
            <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">تحديث</button>
        <a href="{{ route('brands.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
