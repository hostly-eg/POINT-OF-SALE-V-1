@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">حركة المخزون</h3>

    {{-- التنبيهات --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- الأزرار --}}
    <div class="mb-3 d-flex gap-2">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inModal">➕ إضافة كمية</button>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#outModal">➖ سحب كمية</button>
    </div>

    {{-- جدول --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>المنتج</th>
                        <th>النوع</th>
                        <th>الكمية</th>
                        <th>ملاحظات</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                        <tr>
                            <td>{{ $movement->product->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $movement->type == 'in' ? 'success' : 'danger' }}">
                                    {{ $movement->type == 'in' ? 'دخول' : 'خروج' }}
                                </span>
                            </td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->note ?? '-' }}</td>
                            <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">لا توجد حركات حالياً.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal الدخول --}}
<div class="modal fade" id="inModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('stock.in') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">إضافة للمخزون</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>المنتج</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">اختر المنتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>الكمية</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label>ملاحظات</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal السحب --}}
<div class="modal fade" id="outModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('stock.out') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">سحب من المخزون</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>المنتج</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">اختر المنتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>الكمية</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label>ملاحظات</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">سحب</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
