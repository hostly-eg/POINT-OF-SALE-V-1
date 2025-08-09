@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        <h2 class="mb-4">🧾 صفحة الجرد</h2>

        <ul class="nav nav-tabs" id="auditTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="products-tab" data-bs-toggle="tab" href="#products" role="tab">المنتجات</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sales-tab" data-bs-toggle="tab" href="#sales" role="tab">المبيعات</a>
            </li>
        </ul>

        <div class="tab-content mt-4" id="auditTabsContent">
            <!-- 🟦 المنتجات -->
            <div class="tab-pane fade show active" id="products" role="tabpanel">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>كمية المحل (قديم)</th>
                            <th>كمية المحل (جديد)</th>
                            <th>كمية المخزن (قديم)</th>
                            <th>كمية المخزن (جديد)</th>
                            <th>نوع التغيير</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($audits as $row)
                            <tr>
                                <td>{{ $row->product->name ?? '-' }}</td>
                                <td>{{ number_format($row->old_shop_qty ?? 0) }}</td>
                                <td>{{ number_format($row->new_shop_qty ?? 0) }}</td>
                                <td>{{ number_format($row->old_store_qty ?? 0) }}</td>
                                <td>{{ number_format($row->new_store_qty ?? 0) }}</td>
                                <td>{{ $row->change_type }}</td>
                                <td>{{ $row->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">لا توجد حركات حاليًا.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                {{--  {{ $audits->links() }} --}}
                {{ $audits->links('vendor.pagination.bootstrap-5') }} {{-- أو bootstrap-4 --}}

            </div>

            <!-- 🟨 المبيعات -->
            <div class="tab-pane fade" id="sales" role="tabpanel">
                <div class="mb-2">
                    <input id="searchCustomer" class="form-control" placeholder="ابحث باسم صاحب الفاتورة">
                </div>

                <div class="accordion" id="salesAccordion">
                    @foreach ($sales as $sale)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $sale->id }}">
                                <button class="accordion-button collapsed customer-name" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $sale->id }}"
                                    aria-expanded="false" aria-controls="collapse{{ $sale->id }}">
                                    فاتورة رقم {{ $sale->customer_name }} -
                                    {{ \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d H:i') }}
                                </button>
                            </h2>
                            <div id="collapse{{ $sale->id }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $sale->id }}" data-bs-parent="#salesAccordion">
                                <div class="accordion-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>اسم العميل</th>
                                                <th>المنتج</th>
                                                <th>الكمية</th>
                                                <th>السعر</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sale->items as $item)
                                                <tr>
                                                    <td>{{ Str::lower($sale->customer_name) }}</td>
                                                    {{--                               @foreach ($sale as $item2)

                                                    @endforeach --}}
                                                    <td>{{ $item->product->name ?? 'غير موجود' }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ $item->price }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchCustomer');
        const items = document.querySelectorAll('.invoice-item');

        // توحيد بسيط لحروف العربية (ا/أ/إ/آ, ى/ي, ة/ه)
        const normalize = s => (s || '').toString()
            .toLowerCase().replace(/\s+/g, ' ').trim()
            .replace(/[إأآا]/g, 'ا').replace(/ى/g, 'ي').replace(/ة/g, 'ه');

        function applyFilter() {
            const q = normalize(input.value);
            items.forEach(el => {
                const name = el.dataset.customer || el.querySelector('.customer-name')?.textContent ||
                    '';
                el.style.display = normalize(name).includes(q) ? '' : 'none';
            });
        }

        input.addEventListener('input', applyFilter);
    });
</script>
