@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>المستخدمين</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userModal">إضافة مستخدم</button>

        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="ابحث عن اسم، هاتف أو شركة">
        </div>

        <table class="table table-bordered" id="usersTable">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الهاتف</th>
                    <th>الشركة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr id="user-{{ $user->id }}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->company }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-phone="{{ $user->phone }}"
                                data-company="{{ $user->company }}">
                                تعديل
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}">حذف</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="userForm">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة / تعديل مستخدم</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <input type="hidden" id="user_id">
                        <div class="mb-3">
                            <label>الاسم</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label>الهاتف</label>
                            <input type="text" class="form-control" id="phone" required>
                        </div>
                        <div class="mb-3">
                            <label>الشركة</label>
                            <input type="text" class="form-control" id="company" required>
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
            // إضافة / تعديل مستخدم
            $('#userForm').on('submit', function(e) {
                e.preventDefault();

                let id = $('#user_id').val();
                let method = $('#_method').val();
                let url = (method === 'POST') ? "{{ route('users.store') }}" : `/users/${id}`;
                let name = $('#name').val();
                let phone = $('#phone').val();
                let company = $('#company').val();

                $.ajax({
                    url: url,
                    method: (method === 'POST') ? 'POST' : 'PUT',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name,
                        phone: phone,
                        company: company
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('حدث خطأ!');
                    }
                });
            });

            // تعبئة البيانات عند التعديل
            $('.edit-btn').on('click', function() {
                $('#userModal').modal('show');
                $('#user_id').val($(this).data('id'));
                $('#name').val($(this).data('name'));
                $('#phone').val($(this).data('phone'));
                $('#company').val($(this).data('company'));
                $('#_method').val('PUT');
            });

            // حذف مستخدم
            $('.delete-btn').on('click', function() {
                if (!confirm('هل أنت متأكد من الحذف؟')) return;

                let id = $(this).data('id');

                $.ajax({
                    url: `/users/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $(`#user-${id}`).remove();
                    },
                    error: function() {
                        alert('حدث خطأ أثناء الحذف!');
                    }
                });
            });

            // تصفير الفورم عند الفتح
            $('#userModal').on('show.bs.modal', function() {
                $('#userForm')[0].reset();
                $('#_method').val('POST');
                $('#user_id').val('');
            });

            // بحث مباشر في الجدول
            $('#searchInput').on('keyup', function() {
                let value = $(this).val().toLowerCase();

                $('#usersTable tbody tr').filter(function() {
                    $(this).toggle(
                        $(this).text().toLowerCase().indexOf(value) > -1
                    );
                });
            });
        });
    </script>
@endsection
