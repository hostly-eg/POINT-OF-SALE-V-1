{{-- @extends('layouts.app')

@section('content') --}}

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- المنتجات -->
        <div dir="rtl" class="col-12 col-lg-6 border p-3" style="height: 100vh; overflow: scroll;">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-5 mb-2">
                    <select class="form-control select2" id="categoryFilter">
                        <option value="">كل التصنيفات</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2 d-flex justify-content-center mb-2 ">
                    <button onclick="resetFilters()" class="btn btn-outline-secondary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                            <path
                                d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                        </svg>
                    </button>
                </div>
                <div class="col-md-5 mb-2">
                    <select class="form-control select2" id="brandFilter">
                        <option value="">كل البراندات</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 mb-2">
                    <input type="text" id="productSearch" class="form-control" placeholder="ابحث عن المنتج">
                </div>
            </div>

            <div class="row" id="productList">
                @foreach ($products as $product)
                    <div class="col-md-3 mb-3 product-item" data-brand="{{ $product->brand_id }}"
                        data-category="{{ $product->category_id }}">
                        <div class="card text-center"
                            onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->quantity }})">
                            <div class="card-body  rounded-3 " style="background: #ebebeb;">
                                <span class="badge bg-danger">الكمية: {{ $product->quantity }}</span>
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded my-2"
                                    style="height: 80px;">
                                <h6 class="text-dark text-center">{{ $product->name }}</h6>
                                <strong>{{ number_format($product->price, 2) }} ج</strong>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- السلة -->
        <div class="col-12 col-lg-6 d-flex flex-column justify-content-between">
            <table class="table table-bordered">
                <thead class="table-primary text-center">
                    <tr>
                        <th>اسم العنصر</th>
                        <th>المخزون</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>سعر البيع</th>
                        <th>خصم</th>
                        <th>ضريبة</th>
                        <th> الأجمالي</th>
                        <th>✖</th>
                    </tr>
                </thead>
                <tbody id="cartBody"></tbody>
            </table>

            <div dir="rtl" class="mt-3 text-end mb-4">
                <div class="d-flex align-items-center justify-content-around">
                    <div class="d-flex flex-row-reverse">
                        <span id="totalQty" class="mx-2 fs-5"> 0 </span>
                        <h5>
                            الكمية :
                        </h5>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        <span id="totalAmount" class="mx-2 fs-5">0.00ج</span>
                        <h5>المبلغ الإجمالي:
                        </h5>
                    </div>
                </div>
                <button onclick="printInvoice()" class="btn btn-outline-dark col-12 mb-3">فاتورة </button>
                <div class="d-flex align-items-center col-12">

                    <form class="col-12 " method="POST" action="{{ route('pos.sell') }}"
                        onsubmit="return prepareFormData()">

                        <div class="mb-2">
                            <input type="text" class="form-control mb-3" id="customerName" name="customer_name"
                                placeholder="اسم العميل" required>
                        </div>
                        @csrf
                        <input type="hidden" name="items_json" id="items_json">
                        <button class="btn btn-outline-dark col-12">دفع </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let cart = [];

    function addToCart(id, name, price, stock) {
        const exists = cart.find(item => item.id === id);
        if (exists) {
            if (exists.qty < stock) {
                exists.qty++;
            }
        } else {
            cart.push({
                id,
                name,
                price,
                selling_price: price,
                qty: 1,
                stock,
                discount: 0,
                tax: 0
            });
        }
        renderCart();
    }

    function updateSellingPrice(id, value) {
        const item = cart.find(i => i.id === id);
        const price = parseFloat(value);
        if (item && !isNaN(price) && price >= 0) {
            item.selling_price = price;
            renderCart();
        }
    }

    function removeItem(id) {
        cart = cart.filter(item => item.id !== id);
        renderCart();
    }

    function increaseQty(id) {
        const item = cart.find(i => i.id === id);
        if (item && item.qty < item.stock) {
            item.qty++;
            renderCart();
        }
    }

    function decreaseQty(id) {
        const item = cart.find(i => i.id === id);
        if (item && item.qty > 1) {
            item.qty--;
            renderCart();
        }
    }

    function updateQty(id, newQty) {
        const item = cart.find(i => i.id === id);
        const qty = parseInt(newQty);
        if (item && qty > 0 && qty <= item.stock) {
            item.qty = qty;
            renderCart();
        } else {
            alert("الكمية غير صالحة أو أكبر من المخزون المتاح");
        }
    }

    function renderCart() {
        const tbody = document.getElementById('cartBody');
        tbody.innerHTML = '';
        let totalQty = 0,
            totalAmount = 0,
            totalDiscount = 0;

        cart.forEach(item => {
            const subtotal = (item.selling_price - item.discount + item.tax) * item.qty;
            totalQty += item.qty;
            totalAmount += subtotal;
            totalDiscount += item.discount * item.qty;

            tbody.innerHTML += `
                <tr class="text-center">
                    <td>${item.name}</td>
                    <td>${item.stock}</td>
                    <td>
                    <div class="d-flex align-items-center justify-content-center">
                        <button onclick="decreaseQty(${item.id})" class="btn btn-sm btn-outline-secondary">-</button>
                        <input type="number" min="1" max="${item.stock}" value="${item.qty}" 
                            onchange="updateQty(${item.id}, this.value)" 
                            class="form-control form-control-sm mx-1 text-center" 
                            style="width: 60px;">
                        <button onclick="increaseQty(${item.id})" class="btn btn-sm btn-outline-secondary">+</button>
                    </div>
                    </td>
                    <td>${item.price}</td>
                    <td><input type="number" value="${item.selling_price}" onchange="updateSellingPrice(${item.id}, this.value)" class="form-control form-control-sm text-center" style="width: 80px;"></td>
                    <td>${item.discount}</td>
                    <td>${item.tax}</td>
                    <td>${subtotal.toFixed(2)}</td>
                    <td><button class="btn btn-sm btn-danger" onclick="removeItem(${item.id})">🗑️</button></td>
                </tr>`;
        });

        document.getElementById('totalQty').innerText = totalQty;
        document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
    }

    function prepareFormData() {
        if (cart.length === 0) {
            alert("السلة فارغة!");
            return false;
        }

        const jsonInput = document.getElementById("items_json");
        const itemsData = cart.map(item => ({
            product_id: item.id,
            quantity: item.qty,
            price: item.price,
            selling_price: item.selling_price
        }));

        jsonInput.value = JSON.stringify(itemsData);
        return true;
    }

    function printInvoice() {
        if (cart.length === 0) {
            alert("السلة فارغة!");
            return;
        }

        const customerName = document.getElementById("customerName").value || "غير محدد";

        let totalQty = 0;
        let totalAmount = 0;
        let tableRows = '';

        cart.forEach(item => {
            const subtotal = item.qty * item.selling_price;
            totalQty += item.qty;
            totalAmount += subtotal;

            tableRows += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td>${item.selling_price.toFixed(2)}</td>
                    <td>${subtotal.toFixed(2)}</td>
                </tr>
            `;
        });

        const invoiceHTML = `
        <html dir="rtl">
        <head>
            <style>
                body { font-family: Arial; padding: 20px; }
                h2 { text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                .totals { margin-top: 20px; }
            </style>
            <title>اولاد الشيخ </title>
        </head>
        <body>
            <h2>فاتورة بيع</h2>
            <p><strong>اسم المحل:</strong> أولاد الشيخ</p>
            <p><strong>اسم العميل:</strong> ${customerName}</p>
            <p><strong>التاريخ:</strong> ${new Date().toLocaleString('ar-EG')}</p>

            <table>
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الكمية</th>
                        <th>سعر البيع</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>

            <div class="totals">
                <p><strong>إجمالي الكمية:</strong> ${totalQty}</p>
                <p><strong>المبلغ الإجمالي:</strong> ${totalAmount.toFixed(2)} ج</p>
            </div>

            <p style="text-align: center; margin-top: 40px;">شكراً لتعاملكم معنا 🌟</p>
        </body>
        </html>`;

        const printWindow = window.open('', '_blank', 'width=800,height=900');
        printWindow.document.write(invoiceHTML);
        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 1000);
    }
</script>

{{-- @endsection --}}