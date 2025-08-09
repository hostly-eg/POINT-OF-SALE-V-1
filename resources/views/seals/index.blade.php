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
                                <td>
                                    <a href="javascript:void(0)" class="audit-link text-primary text-decoration-underline"
                                        data-id="{{ $row->id }}">
                                        {{ $row->change_type }}
                                    </a>
                                </td>
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
            <div class="modal fade" id="auditModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">تفاصيل العملية</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="auditBody">
                                <div class="text-center p-3">جار التحميل…</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('click', async (e) => {
                    const link = e.target.closest('.audit-link');
                    if (!link) return;
                    e.preventDefault();

                    const id = link.dataset.id;
                    // ... باقي كود الفetch وفتح المودال اللي عملناه قبل كده ...
                });
            </script>

            <script>
                // تأكد من تحميل bootstrap.bundle.js
                document.addEventListener('click', async (e) => {
                    const a = e.target.closest('.audit-link');
                    if (!a) return;
                    e.preventDefault();

                    const id = a.dataset.id;
                    const modalEl = document.getElementById('auditModal');
                    const bodyEl = document.getElementById('auditBody');
                    bodyEl.innerHTML = '<div class="text-center p-3">جار التحميل…</div>';

                    try {
                        const res = await fetch('{{ url('/stock-audits') }}/' + id + '/details');
                        const d = await res.json();

                        const diffShop = (d.quantities.shop_new ?? 0) - (d.quantities.shop_old ?? 0);
                        const diffStore = (d.quantities.store_new ?? 0) - (d.quantities.store_old ?? 0);

                        bodyEl.innerHTML = `
      <div class="row g-3">
        <div class="col-md-6">
          <h6 class="mb-1">العملية</h6>
          <div>النوع: <strong>${d.change_type || '-'}</strong></div>
          <div>الوقت: <strong>${d.created_at || '-'}</strong></div>
        </div>
        <div class="col-md-6">
          <h6 class="mb-1">المنتج</h6>
          <div>الاسم: <strong>${d.product?.name ?? '-'}</strong></div>
          <div>السعر: <strong>${d.product?.price ?? '-'}</strong></div>
        </div>
        <div class="col-12"><hr></div>
        <div class="col-md-6">
          <h6 class="mb-1">المحل</h6>
          <div>قديم: ${d.quantities.shop_old ?? 0}</div>
          <div>جديد: ${d.quantities.shop_new ?? 0}</div>
          <div>الفرق: <strong>${diffShop > 0 ? '+'+diffShop : diffShop}</strong></div>
        </div>
        <div class="col-md-6">
          <h6 class="mb-1">المخزن</h6>
          <div>قديم: ${d.quantities.store_old ?? 0}</div>
          <div>جديد: ${d.quantities.store_new ?? 0}</div>
          <div>الفرق: <strong>${diffStore > 0 ? '+'+diffStore : diffStore}</strong></div>
        </div>
        ${d.sale ? `
                                                                                <div class="col-12"><hr></div>
                                                                                <div class="col-md-6">
                                                                                  <h6 class="mb-1">العميل</h6>
                                                                                  <div>الاسم: <strong>${d.sale.customer_name ?? '-'}</strong></div>
                                                                                  <div>الفاتورة: #${d.sale.id}</div>
                                                                                  <div>قيمة العملية: ${d.sale.total_price}</div>
                                                                                  <div>المدفوع/المرتجع: ${d.sale.paid}</div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                  <h6 class="mb-1">تفاصيل البند</h6>
                                                                                  <div>كمية: ${d.sale_item?.quantity ?? '-'}</div>
                                                                                  <div>سعر: ${d.sale_item?.price ?? '-'}</div>
                                                                                  <div>سعر البيع: ${d.sale_item?.selling_price ?? '-'}</div>
                                                                                  <div>المكان: ${d.sale_item?.location ?? '-'}</div>
                                                                                </div>` : ''}

        ${d.movement ? `
                                                                                <div class="col-12"><hr></div>
                                                                                <div class="col-md-6">
                                                                                  <h6 class="mb-1">تحويل مخزون</h6>
                                                                                  <div>النوع: ${d.movement.type}</div>
                                                                                  <div>الكمية: ${d.movement.quantity}</div>
                                                                                  <div>ملاحظة: ${d.movement.note ?? '-'}</div>
                                                                                </div>` : ''}
      </div>
    `;
                    } catch (err) {
                        bodyEl.innerHTML = '<div class="text-danger">تعذر تحميل التفاصيل.</div>';
                    }

                    new bootstrap.Modal(modalEl).show();
                });
            </script>

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
