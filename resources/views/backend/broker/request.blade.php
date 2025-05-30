@extends('backend.user.layout')
@section('broker-request-content')
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
                                <h4 class="card-title">Deal Request Logs</h4>
                            </div>
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <button id="bulk-reject-btn" class="btn btn-danger btn-sm">Bulk Unapprove (Reject)</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table datatable" id="broker-request-table">
                                <thead class="">
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Buyer Name</th>
                                        <th>Buyer Email</th>
                                        <th>Buyer Phone</th>
                                        <th>Deal Title</th>
                                        <th>Status</th>
                                        <th>Request At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dealRequests as $request)
                                        <tr>
                                            <td><input type="checkbox" class="row-checkbox" value="{{ $request->id }}">
                                            </td>
                                            <td>{{ $request->user->name ?? 'N/A' }}</td>
                                            <td>{{ $request->user->email ?? 'N/A' }}</td>
                                            <td>{{ $request->user->phone ?? 'N/A' }}</td>
                                            <td title="{{ $request->deal->gcs_deal_id ?? 'N/A' }}" style="cursor: pointer;">
                                                {{ \Illuminate\Support\Str::limit($request->deal->gcs_deal_id ?? 'N/A', 30, '...') }}
                                            <td>
                                                <span id="status-{{ $request->id }}"
                                                    class="badge 
                                                    @if ($request->status == 'pending') bg-info
                                                    @elseif ($request->status == 'rejected') bg-danger
                                                    @else bg-success @endif">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at }}</td>
                                            <td>
                                                @if ($request->status == 'pending')
                                                    <button class="btn btn-success btn-sm update-status"
                                                        data-id="{{ $request->id }}"
                                                        data-status="approved">Approve</button>

                                                    <button class="btn btn-danger btn-sm update-status"
                                                        data-id="{{ $request->id }}"
                                                        data-status="rejected">Reject</button>
                                                @else
                                                    <span class="badge bg-secondary">Finalized</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($dealRequests->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center">No deal requests found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#broker-request-table').DataTable({
                pageLength: 100
            });

            $(document).on("click", ".update-status", function() {
                let requestId = $(this).data("id");
                let newStatus = $(this).data("status");
                let button = $(this);

                $.ajax({
                    url: "{{ route('deal-requests.update-status', ':id') }}".replace(':id',
                        requestId),
                    type: "PUT",
                    data: {
                        status: newStatus,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Update status badge
                        $(`#status-${requestId}`)
                            .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(
                                1)) // Capitalize first letter
                            .removeClass("bg-info bg-danger bg-success")
                            .addClass(newStatus === 'approved' ? 'bg-success' : 'bg-danger');

                        // Remove buttons and show "Finalized"
                        button.closest("td").html(
                            '<span class="badge bg-secondary">Finalized</span>');

                        alert(response.message);
                    },
                    error: function(error) {
                        alert("Error updating status.");
                    }
                });
            });


            let table = $('#datatable_2').DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }]
            });

            $('#select-all').on('click', function(e) {
                const isChecked = $(this).is(':checked');
                $('.row-checkbox').prop('checked', isChecked);
            });

            $(document).on('change', '.row-checkbox', function() {
                const total = $('.row-checkbox').length;
                const checked = $('.row-checkbox:checked').length;
                $('#select-all').prop('checked', total === checked);
            });

            $('#bulk-reject-btn').on('click', function() {
                let selectedIds = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    alert('Please select at least one record.');
                    return;
                }

                if (!confirm('Are you sure you want to unapprove (reject) selected users?')) {
                    return;
                }

                $.ajax({
                    url: "{{ route('deal-requests.bulk-update') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds,
                        status: 'rejected'
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function() {
                        alert('Something went wrong.');
                    }
                });
            });
            
        });
    </script>
@endsection
