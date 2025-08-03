@extends('layouts.app')

@section('content')
<div class="container text-end">
    <h2>كل المنتجات</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">إضافة منتج جديد</a>

    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>القسم</th>
                <th>البراند</th>
                <th>السعر</th>
                <th>الربح (%)</th>
                <th>الكمية</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="50">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>{{ $product->brand->name ?? '-' }}</td>
                <td>{{ $product->price }} ج.م</td>
                <td>{{ $product->profit_margin }}%</td>
                <td>{{ $product->quantity }}</td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                        style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('حذف المنتج؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">لا توجد منتجات حالياً</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
