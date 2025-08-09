@extends('layouts.app')

@section('content')
    <div class="container" dir="rtl">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">تعديل المخزون (تحويل بين المحل والمخزن)</h3>
            <a href="{{ route('stock.index') }}" class="btn btn-secondary">رجوع</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stock.transfer') }}" method="POST" id="transferForm">
            @csrf

            <div id="rows">
                <div class="row g-2 align-items-end mb-2 one-row">
                    <div class="col-md-5">
                        <label class="form-label">المنتج</label>
                        <select name="items[0][product_id]" class="form-select" required>
                            <option value="">اختر المنتج</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->name }} — (المحل: {{ $p->quantity_shop }}, المخزن: {{ $p->quantity_store }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">الكمية</label>
                        <input type="number" name="items[0][qty]" class="form-control" min="1" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">الاتجاه</label>
                        <select name="items[0][direction]" class="form-select" required>
                            <option value="shop_to_store">من المحل إلى المخزن</option>
                            <option value="store_to_shop">من المخزن إلى المحل</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 remove-row">حذف</button>
                    </div>
                </div>
            </div>

            <button type="button" id="addRow" class="btn btn-outline-primary mb-3">إضافة صف</button>
            <button type="submit" class="btn btn-primary mb-3">حفظ التحويل</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {
            let idx = 1;

            document.getElementById('addRow').addEventListener('click', function() {
                const rows = document.getElementById('rows');
                const first = rows.querySelector('.one-row');
                const clone = first.cloneNode(true);

                // امسح القيم
                clone.querySelectorAll('input').forEach(i => i.value = '');
                clone.querySelectorAll('select').forEach(s => {
                    // رجّع لأول خيار
                    s.selectedIndex = 0;
                });

                // حدّث أسماء الحقول
                clone.querySelectorAll('select, input').forEach(el => {
                    el.name = el.name.replace(/\[\d+\]/, '[' + idx + ']');
                });

                rows.appendChild(clone);
                idx++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    const all = document.querySelectorAll('#rows .one-row');
                    if (all.length > 1) {
                        e.target.closest('.one-row').remove();
                    }
                }
            });
        })();
    </script>
@endsection
