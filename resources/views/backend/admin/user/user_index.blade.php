@extends('backend.admin.layout')
@section('admin-user-index-content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <div class="container-xxl">
        @if (Session::get('success'))
            <div class=" alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Users</h4>
                                <a href="{{ route('users.create') }}">
                                    <button type="button" class="btn btn-info">Create User</button>
                                </a>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <button type="button" class="btn btn-danger mb-3" id="delete_selected">Delete Selected</button>
                        <div class="table-responsive">
                            <table class="table datatable" id="user-index-table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all"></th>
                                        <th>UserName</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>NDA Accepted</th>
                                        <th>User IP Log</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="user_checkbox" value="{{ $user->id }}">
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->roles_id == '1')
                                                    Admin
                                                @elseif($user->roles_id == '2')
                                                    Broker
                                                @elseif($user->roles_id == '3')
                                                    Seller
                                                @elseif($user->roles_id == '4')
                                                    Buyer
                                                @elseif($user->roles_id == '5')
                                                    Employee
                                                @endif
                                            </td>
                                            <td><span class="badge bg-success">YES</span> {{ $user->created_at }}</td>
                                            <td>{{ $user->ip_address }}</td>
                                            <td>
                                                @if ($user->status == 'active')
                                                    <span class="badge bg-primary">{{ $user->status }}</span>
                                                @elseif($user->status == 'inactive')
                                                    <span class="badge bg-warning">{{ $user->status }}</span>
                                                @else
                                                    Null
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-primary btn-sm"
                                                    style="height:30px;width:30px;border-radius:50%" title="edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('users.destroy', [$user->id]) }}"
                                                    class="d-inline-block individualDeleteForm">
                                                    @csrf
                                                    <button type="button" class="btn btn-danger btn-sm dltBtn"
                                                        data-id="{{ $user->id }}"
                                                        style="height:30px;width:30px;border-radius:50%" title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $('#user-index-table').DataTable({
            pageLength: 100
        });

        $(document).ready(function() {

            $('.dltBtn').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, this user cannot be recovered!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });


            $('#select_all').on('change', function() {
                $('.user_checkbox').prop('checked', this.checked);
            });

            $('.user_checkbox').on('change', function() {
                const total = $('.user_checkbox').length;
                const checked = $('.user_checkbox:checked').length;
                $('#select_all').prop('checked', total === checked);
            });


            $('#delete_selected').on('click', function() {
                const selectedIds = $('.user_checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    swal("No users selected!", "Please select at least one user to delete.", "warning");
                    return;
                }

                swal({
                    title: "Are you sure?",
                    text: "All selected users will be permanently deleted.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('users.massDelete') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                user_ids: selectedIds
                            },
                            success: function(response) {
                                swal("Deleted!", "Selected users have been deleted.",
                                        "success")
                                    .then(() => {
                                        location.reload();
                                    });
                            },
                            error: function(xhr) {
                                swal("Error!", "Something went wrong. Try again.",
                                    "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
