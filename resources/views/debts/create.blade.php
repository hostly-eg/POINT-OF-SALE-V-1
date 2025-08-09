@extends('layouts.app')

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <h4 class="mb-4">إنشاء مديونية شخصية</h4>

    <form action="{{ route('personal-debts.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">اسم الشركة</label>
            <input type="text" name="company_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">التاريخ</label>
            <input type="date" name="debt_date" class="form-control" required>
        </div>

        <hr>

        <h5>المنتجات</h5>
        <div id="products-repeater">
            <div class="product-row row mb-2">
                <div class="col-md-4">
                    <select name="products[0][product_id]" class="form-control" required>
                        <option value="">اختر منتج</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="products[0][quantity]" class="form-control quantity" placeholder="الكمية" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="products[0][price]" class="form-control price" placeholder="السعر" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-product">حذف</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" id="add-product">إضافة منتج</button>

        <div class="mb-3">
            <label class="form-label">المبلغ المدفوع</label>
            <input type="number" name="paid" class="form-control" id="paid" value="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">المبلغ المتبقي</label>
            <input type="number" name="remaining" class="form-control" id="remaining" readonly>
        </div>

        <button type="submit" class="btn btn-primary">حفظ</button>
    </form>
</div>

@endsection

@section('scripts')
<script>
    let productIndex = 1;

    document.getElementById('add-product').addEventListener('click', function () {
        let newRow = document.querySelector('.product-row').cloneNode(true);

        newRow.querySelectorAll('input, select').forEach(function (input) {
            input.name = input.name.replace(/\[\d+\]/, `[${productIndex}]`);
            input.value = '';
        });

        document.getElementById('products-repeater').appendChild(newRow);
        productIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product')) {
            let rows = document.querySelectorAll('.product-row');
            if (rows.length > 1) {
                e.target.closest('.product-row').remove();
                calculateRemaining();
            }
        }
    });

    document.addEventListener('input', function () {
        calculateRemaining();
    });

function calculateRemaining() {
    let total = 0;
    document.querySelectorAll('.product-row').forEach(function (row) {
        let price = parseFloat(row.querySelector('.price')?.value) || 0;
        total += price; // بنجمع إجمالي السطر مباشرة
    });

    let paid = parseFloat(document.querySelector('[name="paid"]')?.value) || 0;
    let remaining = total - paid;

    document.querySelector('[name="remaining"]').value = remaining.toFixed(2);
}

</script>
@endsection
