@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- نموذج إضافة / تعديل -->
        <div class="card mb-4">
            <div class="card-header">{{ isset($editExpense) ? 'تعديل النفقة' : 'إضافة نفقة جديدة' }}</div>
            <div class="card-body">
                <form
                    action="{{ isset($editExpense) ? route('expenses.update', $editExpense->id) : route('expenses.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($editExpense))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <input type="text" class="form-control" name="description" id="description"
                            value="{{ old('description', $editExpense->description ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">المبلغ</label>
                        <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                            value="{{ old('amount', $editExpense->amount ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">التاريخ</label>
                        <input type="date" class="form-control" name="expense_date" id="date" 
                            value="{{ old('expense_date', isset($editExpense) && $editExpense->expense_date ? \Carbon\Carbon::parse($editExpense->expense_date)->format('Y-m-d') : now()->format('Y-m-d')) }}">

                    </div>

                    <button type="submit" class="btn btn-primary">
                        {{ isset($editExpense) ? 'تحديث' : 'إضافة' }}
                    </button>

                    @if (isset($editExpense))
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- جدول النفقات -->
        <div class="card">
            <div class="card-header">قائمة النفقات</div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>الوصف</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->description }}</td>
                                <td>{{ number_format($expense->amount, 2) }} ج</td>
                                <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('expenses.edit', $expense->id) }}"
                                        class="btn btn-sm btn-warning">تعديل</a>
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                        style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">لا توجد نفقات مسجلة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
