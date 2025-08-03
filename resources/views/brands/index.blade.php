@extends('layouts.app')

@section('content')
<div class="container text-end">
    <h2>كل البراندات</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('brands.create') }}" class="btn btn-primary mb-3">إضافة براند جديد</a>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم البراند</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($brands as $brand)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $brand->name }}</td>
                <td>
                    <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('حذف البراند؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3">لا توجد بيانات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
