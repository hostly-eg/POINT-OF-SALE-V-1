@extends('layouts.app')

@section('content')
<div class="container text-end">
    <h2>تعديل المنتج</h2>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>اسم المنتج</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label>القسم</label>
            <select name="category_id" class="form-select" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @if($product->category_id == $cat->id) selected @endif>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>البراند</label>
            <select name="brand_id" class="form-select" required>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>السعر</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="mb-3">
            <label>هامش الربح (%)</label>
            <input type="number" step="0.01" name="profit_margin" class="form-control" value="{{ $product->profit_margin }}" required>
        </div>

        <div class="mb-3">
            <label>الكمية</label>
            <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}" required>
        </div>

        <div class="mb-3">
            <label>صورة المنتج</label>
            @if($product->image)
                <div><img src="{{ asset('storage/' . $product->image) }}" width="80"></div>
            @endif
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <button type="submit" class="btn btn-primary">تحديث</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
