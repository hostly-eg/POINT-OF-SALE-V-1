@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">إدارة المديونيات</h2>

        <!-- التابات -->
        <ul class="nav nav-tabs" id="debtTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab">مديونيات
                    شخصية</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="customer-tab" data-bs-toggle="tab" href="#customer" role="tab">مديونيات
                    الزبائن</a>
            </li>
        </ul>
                <div class="row mb-3">
                    <div class="col-md-12 ms-auto">
                        <input id="tableSearch" type="text" class="form-control"
                            placeholder="ابحث بالاسم/التاريخ/المبلغ...">
                    </div>
                </div>
        <div class="tab-content mt-4" id="debtTabsContent">
            <!-- مديونيات شخصية -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <a href="{{ route('personal-debts.create') }}" class="btn btn-success mb-3">إضافة مديونية شخصية</a>


                <table class="table table-bordered" id="personalTable">
                    <thead>
                        <tr>
                            <th>اسم الشركة</th>
                            <th>التاريخ</th>
                            <th>السعر الإجمالي</th>
                            <th>المدفوع</th>
                            <th>المتبقي</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personalDebts as $debt)
                            <tr>
                                <td>{{ $debt->company_name }}</td>
                                <td>{{ $debt->debt_date }}</td>
                                <td>{{ $debt->total_amount }}</td>
                                <td>{{ $debt->paid_amount }}</td>
                                <td>{{ $debt->remaining_amount < 0 ? 0 : $debt->remaining_amount }}</td>

                                <td>
                                    @php $rem = (float) ($debt->remaining_amount ?? $debt->remaining ?? 0); @endphp
                                    @if ($rem > 0)
                                        <span class="badge bg-warning text-dark">لم يتم السداد</span>
                                    @else
                                        <span class="badge bg-success">تم السداد</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('personal-debts.edit', $debt->id) }}"
                                        class="btn btn-sm btn-primary">تعديل</a>
                                    <form action="{{ route('personal-debts.destroy', $debt->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                            class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- مديونيات الزبائن -->
            <div class="tab-pane fade" id="customer" role="tabpanel">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اسم الزبون</th>
                            <th>تاريخ البيع</th>
                            <th>المبلغ الإجمالي</th>
                            <th>المدفوع</th>
                            <th>المتبقي</th>
                            <th>الحالة</th>
                            <th>تعديل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customerDebts as $debt)
                            <tr>
                                <td>{{ $debt->customer_name }}</td>
                                <td>{{ $debt->created_at->format('Y-m-d') }}</td>
                                <td>{{ $debt->total_price }}</td>
                                <td>{{ $debt->paid }}</td>
                                <td>{{ $debt->remaining }}</td>
                                <td>
                                    @php $rem = (float) ($debt->remaining_amount ?? $debt->remaining ?? 0); @endphp
                                    @if ($rem > 0)
                                        <span class="badge bg-warning text-dark">لم يتم السداد</span>
                                    @else
                                        <span class="badge bg-success">تم السداد</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- داخل <tr> لكل صف في جدول مديونيات الزبائن -->
                                    <button class="btn btn-sm btn-primary open-paid-modal" data-id="{{ $debt->id }}"
                                        data-customer="{{ $debt->customer_name }}"
                                        data-total="{{ number_format($debt->total_price, 2, '.', '') }}"
                                        data-paid="{{ number_format($debt->paid, 2, '.', '') }}"
                                        data-update-url="{{ route('customer-debts.update', $debt->id) }}">
                                        تعديل
                                    </button>

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="paidModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="paidForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">تحديث المدفوع</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">اسم الزبون</label>
                            <input type="text" id="pm_customer" class="form-control" disabled>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">الإجمالي</label>
                            <input type="number" step="0.01" id="pm_total" class="form-control" disabled>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">المدفوع</label>
                            <input type="number" step="0.01" name="paid" id="pm_paid" class="form-control"
                                required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">المتبقي</label>
                            <input type="number" step="0.01" id="pm_remaining" class="form-control" disabled>
                        </div>
                        <div id="pm_error" class="text-danger small d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.open-paid-modal');
            if (!btn) return;

            // عبّي المودال
            const id = btn.dataset.id;
            const customer = btn.dataset.customer || '';
            const total = parseFloat(btn.dataset.total || '0');
            const paid = parseFloat(btn.dataset.paid || '0');
            const updateUrl = btn.dataset.updateUrl;

            document.getElementById('pm_customer').value = customer;
            document.getElementById('pm_total').value = total.toFixed(2);
            document.getElementById('pm_paid').value = paid.toFixed(2);
            document.getElementById('pm_remaining').value = Math.max(total - paid, 0).toFixed(2);

            const form = document.getElementById('paidForm');
            form.action = updateUrl;

            const pmModal = new bootstrap.Modal(document.getElementById('paidModal'));
            pmModal.show();
        });

        // تحديث المتبقي أثناء الكتابة
        document.getElementById('pm_paid').addEventListener('input', function() {
            const total = parseFloat(document.getElementById('pm_total').value || '0');
            const paid = parseFloat(this.value || '0');
            document.getElementById('pm_remaining').value = Math.max(total - paid, 0).toFixed(2);
        });
    </script>
    <script>
        const searchInput = document.getElementById('tableSearch');
        const tables = [document.getElementById('personalTable'), document.getElementById('customer')];

        function filterTables(q) {
            tables.forEach(tbl => {
                if (!tbl) return;
                const rows = tbl.querySelectorAll('tbody tr');
                rows.forEach(tr => {
                    const text = tr.innerText.toLowerCase();
                    tr.style.display = text.includes(q) ? '' : 'none';
                });
            });
        }

        searchInput.addEventListener('keyup', () => {
            const q = searchInput.value.trim().toLowerCase();
            filterTables(q);
        });
    </script>
@endsection
