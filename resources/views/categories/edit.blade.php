@extends('layouts.app')

@section('content')
<div class="container text-end">
    <h2>تعديل القسم</h2>

    <form method="POST" action="{{ route('categories.update', $category->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">اسم القسم</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">تحديث</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
