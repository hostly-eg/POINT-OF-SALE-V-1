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
        <h2 class="mb-4">تعديل مديونية شخصية</h2>

        <form action="{{ route('personal-debts.update', $debt->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="company_name" class="form-label">اسم الشركة</label>
                <input type="text" name="company_name" class="form-control" value="{{ $debt->company_name }}" required>
            </div>

            <div class="mb-3">
                <label for="debt_date" class="form-label">تاريخ المديونية</label>
                <input type="date" name="debt_date" class="form-control" value="{{ $debt->debt_date }}" required>
            </div>

            <hr>

            <h5>المنتجات</h5>
            <div id="products-repeater">
                @foreach ($debt->items as $index => $item)
                    <div class="product-row row mb-2">
                        <div class="col-md-4">
                            <select name="products[{{ $index }}][product_id]" class="form-control" required>
                                <option value="">اختر منتج</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="products[{{ $index }}][quantity]"
                                class="form-control quantity" value="{{ $item->quantity }}" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="products[{{ $index }}][price]" class="form-control price"
                                value="{{ $item->price }}" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-product">X</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="add-product">إضافة منتج</button>

            <div class="mb-3">
                <label for="paid" class="form-label">المبلغ المدفوع</label>
                <input type="number" name="paid_amount" class="form-control" id="paid"
                    value="{{ $debt->paid_amount }}" required>
            </div>

            <div class="mb-3">
                <label for="remaining" class="form-label">المبلغ المتبقي</label>
                <input type="number" name="remaining_amount" class="form-control" id="remaining"
                    value="{{ max(0, $debt->remaining_amount) }}" readonly>
            </div>

            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        let productIndex = {{ count($debt->items) }};

        document.getElementById('add-product').addEventListener('click', function() {
            let html = `
            <div class="product-row row mb-2">
                <div class="col-md-4">
                    <select name="products[${productIndex}][product_id]" class="form-control" required>
                        <option value="">اختر منتج</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="products[${productIndex}][price]" class="form-control price" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-product">X</button>
                </div>
            </div>
        `;
            document.getElementById('products-repeater').insertAdjacentHTML('beforeend', html);
            productIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.closest('.product-row').remove();
                calculateRemaining();
            }
        });

        document.addEventListener('input', function() {
            calculateRemaining();
        });

        function calculateRemaining() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(function(row) {
                let qty = parseFloat(row.querySelector('.quantity')?.value) || 0;
                let price = parseFloat(row.querySelector('.price')?.value) || 0;
                total += qty * price;
            });

            let paid = parseFloat(document.getElementById('paid')?.value) || 0;
            let remaining = Math.max(total - paid, 0);
            document.getElementById('remaining').value = remaining.toFixed(2);
        }
    </script>
@endsection
