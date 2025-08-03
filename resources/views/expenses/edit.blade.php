@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">تعديل النفقة</div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <input type="text" class="form-control" name="description" value="{{ old('description', $expense->description) }}" required>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">المبلغ</label>
                    <input type="number" step="0.01" class="form-control" name="amount" value="{{ old('amount', $expense->amount) }}" required>
                </div>

                <div class="mb-3">
                    <label for="expense_date" class="form-label">التاريخ</label>
                    <input type="date" class="form-control" name="expense_date"
                        value="{{ old('expense_date', $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') : '') }}"
                        required>
                </div>

                <button type="submit" class="btn btn-primary">تحديث</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
