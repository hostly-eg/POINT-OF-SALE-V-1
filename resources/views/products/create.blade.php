@extends('layouts.app')

@section('content')
    <div class="container text-end">
        <h2>إضافة منتج</h2>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>اسم المنتج</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>القسم</label>
                <select name="category_id" class="form-select select2" required>
                    <option disabled selected>اختر القسم</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>البراند</label>
                <select name="brand_id" class="form-select" required>
                    <option disabled selected>اختر البراند</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>السعر</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>هامش الربح (%)</label>
                <input type="number" step="0.01" name="profit_margin" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>الكمية</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>صورة المنتج</label>
                <input type="file" name="image" class="form-control" onchange="previewImage(event)">
                <img id="preview" src="#" style="max-width: 100px; display:none; margin-top:10px;">
            </div>

            <button type="submit" class="btn btn-success">حفظ المنتج</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">رجوع</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('select').select2({
                width: '100%',
                dir: "rtl",
                placeholder: "اختر من القائمة",
                language: {
                    noResults: function() {
                        return "لا يوجد نتائج";
                    }
                }
            });
        });
    </script>
@endsection
