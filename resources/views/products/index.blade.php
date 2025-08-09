@extends('layouts.app')

@section('content')
    <div class="container text-end">
        <h2>كل المنتجات</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">إضافة منتج جديد</a>

        {{-- ✅ البحث والفلاتر --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="ابحث بالاسم...">
            </div>
            <div class="col-md-3">
                <select id="categoryFilter" class="form-control">
                    <option value="">اختر القسم</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="brandFilter" class="form-control">
                    <option value="">اختر البراند</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button id="resetFilter" class="btn btn-secondary w-100">إعادة تعيين</button>
            </div>
        </div>


        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>القسم</th>
                    <th>البراند</th>
                    <th>موديل السيارة</th>
                    <th>السعر</th>
                    <th>كمية المحل </th>
                    <th>كمية المخزن </th>
                    <th>الإجراءات</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr data-name="{{ $product->name }}" data-category="{{ $product->category->name ?? '' }}"
                        data-brand="{{ $product->brand->name ?? '' }}">
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="50">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>{{ $product->car->name ?? '-' }}</td>
                        <td>{{ $product->price }} ج.م</td>

                        <td>{{ $product->quantity_shop }}</td>
                        <td>{{ $product->quantity_store }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('حذف المنتج؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">لا توجد منتجات حالياً</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function filterProducts() {
                let search = $('#searchInput').val().toLowerCase();
                let category = $('#categoryFilter').val();
                let brand = $('#brandFilter').val();

                $('table tbody tr').each(function() {
                    let name = $(this).find('td:nth-child(2)').text().toLowerCase();
                    let categoryText = $(this).find('td:nth-child(3)').text();
                    let brandText = $(this).find('td:nth-child(4)').text();

                    let matchSearch = name.includes(search);
                    let matchCategory = !category || categoryText === category;
                    let matchBrand = !brand || brandText === brand;

                    if (matchSearch && matchCategory && matchBrand) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            $('#searchInput, #categoryFilter, #brandFilter').on('input change', filterProducts);

            $('#resetFilter').on('click', function() {
                $('#searchInput').val('');
                $('#categoryFilter').val('');
                $('#brandFilter').val('');
                filterProducts(); // Show all rows
            });
        });
    </script>
@endsection
