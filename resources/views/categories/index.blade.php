@extends('layouts.app')

@section('content')
<div class="container text-end">
    <h2>كل الأقسام</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">إضافة قسم جديد</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>الرقم</th>
                <th>الاسم</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')" class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">لا توجد أقسام بعد.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
