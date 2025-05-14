@extends('backend.admin.layout')
@section('admin-request-content')
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
                        <div class="table-responsive">
                            <table class="table datatable" id="datatable_2">
                                <thead class="">
                                    <tr>
                                        <th>Buyer Name</th>
                                        <th>Buyer Email</th>
                                        <th>Deal Title</th>
                                        <th>Status</th>
                                        <th>Request At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dealRequests as $request)
                                        <tr>
                                            <td>{{ $request->user->name ?? 'N/A' }}</td>
                                            <td>{{ $request->user->email ?? 'N/A' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($request->deal->gcs_deal_id ?? 'N/A', 50, '...') }}
                                            </td>
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
                                                        data-id="{{ $request->id }}" data-status="rejected">Reject</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
                            .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1))
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
        });
    </script>
@endsection
