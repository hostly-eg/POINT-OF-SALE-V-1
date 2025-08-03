@extends('layouts.app')

@section('content')
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية القديمة</th>
                            <th>الكمية الجديدة</th>
                            <th>الفرق</th>
                            <th>نوع التغيير</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audits as $audit)
                            <tr>
                                <td>{{ $audit->product->name ?? 'غير موجود' }}</td>
                                <td>{{ $audit->old_quantity }}</td>
                                <td>{{ $audit->new_quantity }}</td>
                                <td>{{ $audit->difference }}</td>
                                <td>{{ $audit->change_type }}</td>
                                <td>{{ $audit->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 🟨 المبيعات -->
            <div class="tab-pane fade" id="sales" role="tabpanel">
                <div class="accordion" id="salesAccordion">
                    @foreach ($sales as $sale)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $sale->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $sale->id }}" aria-expanded="false"
                                    aria-controls="collapse{{ $sale->id }}">
                                    فاتورة رقم {{ $sale->id }} -
                                    {{ \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d H:i') }}
                                </button>
                            </h2>
                            <div id="collapse{{ $sale->id }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $sale->id }}" data-bs-parent="#salesAccordion">
                                <div class="accordion-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>المنتج</th>
                                                <th>الكمية</th>
                                                <th>السعر</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sale->items as $item)
                                                <tr>
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
