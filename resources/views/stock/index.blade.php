@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">حركة المخزون</h3>
            <div dir="ltr" class="input-group" style="max-width: 320px;">
                <input id="liveSearch" type="text" class="form-control" placeholder="ابحث عن المنتج...">
                <span class="input-group-text">بحث</span>
            </div>
            <a href="{{ route('stock.edit') }}" class="btn btn-outline-primary">تعديل المخزون</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <ul class="nav nav-tabs" id="invTabs" role="tablist" dir="rtl">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="shop-tab" data-bs-toggle="tab" data-bs-target="#shop" type="button"
                    role="tab">مخزون المحل</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="store-tab" data-bs-toggle="tab" data-bs-target="#store" type="button"
                    role="tab">مخزون المخزن</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button"
                    role="tab">كل المنتجات</button>
            </li>
        </ul>

        <div class="tab-content border border-top-0 p-3" id="invTabsContent" dir="rtl">

            {{-- مخزون المحل --}}
            <div class="tab-pane fade show active" id="shop" role="tabpanel" aria-labelledby="shop-tab">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th>كمية المحل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ number_format($p->quantity_shop, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">لا توجد منتجات.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- مخزون المخزن --}}
            <div class="tab-pane fade" id="store" role="tabpanel" aria-labelledby="store-tab">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th>كمية المخزن</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ number_format($p->quantity_store, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">لا توجد منتجات.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- كل المنتجات --}}
            <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th>السعر</th>
                                <th>المحل</th>
                                <th>المخزن</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ number_format($p->price, 2) }}</td>
                                    <td>{{ number_format($p->quantity_shop, 2) }}</td>
                                    <td>{{ number_format($p->quantity_store, 2) }}</td>
                                    <td>{{ number_format($p->quantity_shop + $p->quantity_store, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد منتجات.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function() {
            const searchInput = document.getElementById('liveSearch');
            if (!searchInput) return;

            // دالة ديباونس عشان ما نفلترش كل كيستروك
            let t;
            const debounce = (fn, delay = 200) => (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), delay);
            };

            function filterTable(table, term) {
                term = term.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(tr => {
                    const text = tr.innerText.toLowerCase();
                    tr.style.display = text.includes(term) ? '' : 'none';
                });
            }

            const handler = debounce(() => {
                const term = searchInput.value.trim();
                // هفلتر الجدول اللي ظاهر في التاب الحالي فقط
                const activeTab = document.querySelector('.tab-pane.active.show');
                if (!activeTab) return;

                const table = activeTab.querySelector('table');
                if (table) filterTable(table, term);
            }, 200);

            searchInput.addEventListener('input', handler);

            // لما تغيّر التاب، أفلتر تاني حسب القيمة الموجودة
            document.getElementById('invTabs')?.addEventListener('click', () => {
                setTimeout(() => {
                    const term = searchInput.value.trim();
                    const activeTab = document.querySelector('.tab-pane.active.show');
                    const table = activeTab?.querySelector('table');
                    if (table) filterTable(table, term);
                }, 50);
            });
        })();
    </script>
@endsection
