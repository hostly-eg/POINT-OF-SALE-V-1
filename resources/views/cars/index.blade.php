@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>العربيات</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#carModal">إضافة سيارة</button>

        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="ابحث عن اسم سيارة">
        </div>

        <table class="table table-bordered" id="carsTable">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cars as $car)
                    <tr id="car-{{ $car->id }}">
                        <td>{{ $car->name }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $car->id }}"
                                data-name="{{ $car->name }}">
                                تعديل
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $car->id }}">حذف</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="carModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="carForm">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة / تعديل سيارة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <input type="hidden" id="car_id">
                        <div class="mb-3">
                            <label>اسم السيارة</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            // إضافة / تعديل سيارة
            $('#carForm').on('submit', function(e) {
                e.preventDefault();

                let id = $('#car_id').val();
                let method = $('#_method').val();
                let url = (method === 'POST') ? "{{ route('cars.store') }}" : `/cars/${id}`;
                let name = $('#name').val();

                $.ajax({
                    url: url,
                    method: (method === 'POST') ? 'POST' : 'PUT',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('حدث خطأ!');
                    }
                });
            });

            // تعبئة البيانات عند التعديل
            $('.edit-btn').on('click', function() {
                $('#carModal').modal('show');
                $('#car_id').val($(this).data('id'));
                $('#name').val($(this).data('name'));
                $('#_method').val('PUT');
            });

            // حذف سيارة
            $('.delete-btn').on('click', function() {
                if (!confirm('هل أنت متأكد من الحذف؟')) return;

                let id = $(this).data('id');

                $.ajax({
                    url: `/cars/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $(`#car-${id}`).remove();
                    },
                    error: function() {
                        alert('حدث خطأ أثناء الحذف!');
                    }
                });
            });

            // reset form
            $('#carModal').on('show.bs.modal', function() {
                $('#carForm')[0].reset();
                $('#_method').val('POST');
                $('#car_id').val('');
            });

            // بحث مباشر في الجدول
            $('#searchInput').on('keyup', function() {
                let value = $(this).val().toLowerCase();

                $('#carsTable tbody tr').filter(function() {
                    $(this).toggle(
                        $(this).text().toLowerCase().indexOf(value) > -1
                    );
                });
            });
        });
    </script>
@endsection
