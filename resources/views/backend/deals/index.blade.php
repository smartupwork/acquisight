@extends('backend.admin.layout')
@section('admin-deals-index-content')
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
                                <h4 class="card-title">Add Deals</h4>
                                <a href="{{ route('deals.create') }}">
                                    <button type="button" class="btn btn-info">Create Deal</button>
                                </a>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table datatable" id="datatable_2">
                                <thead class="">
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deals as $deal)
                                        <tr>
                                            <td>{{ $deal->name }}</td>
                                            <td>{{ $deal->description }}</td>
                                            <td>
                                                @if ($deal->status == 1)
                                                    <span class="badge bg-primary" style="">Active</span>
                                                @else
                                                    <span class="badge bg-warning">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $deal->created_at }}</td>
                                            <td class="d-flex justify-evenly-space align-items-center" style="gap:5px;">
                                                <a href="{{ route('deals.view', $deal->id) }}"
                                                    class="btn btn-success btn-sm float-left mr-1"
                                                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                    title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>

                                                <a href="javascript:void(0)" class="btn btn-success btn-sm float-left mr-1"
                                                    style="height:30px; width:30px; border-radius:50%" data-toggle="tooltip"
                                                    title="Copy Deal Link" data-placement="bottom"
                                                    onclick="copyToClipboard('{{ url('/') . '/copy/link/' . $deal->gcs_deal_id }}')">
                                                    <i class="fas fa-copy"></i>
                                                </a>

                                                <a href="javascript:void(0)" class="btn btn-primary btn-sm float-left mr-1"
                                                    style="height:30px; width:30px;border-radius:50%" data-bs-toggle="modal"
                                                    data-bs-target="#editDealModal"
                                                    onclick="loadDealData({{ $deal->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a href="{{ route('deals.inviteView', $deal->id) }}"
                                                    class="btn btn-info btn-sm float-left mr-1"
                                                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                    title="invite" data-placement="bottom"><i
                                                        class="fas fa-envelope"></i></a>

                                                <form method="POST" action="{{ route('deals.destroy', [$deal->id]) }}">
                                                    @csrf
                                                    <button class="btn btn-danger btn-sm dltBtn"
                                                        data-id={{ $deal->id }}
                                                        style="height:30px; width:30px;border-radius:50%"
                                                        data-toggle="tooltip" data-placement="bottom" title="Delete"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <button type="button" class="btn btn-sm btn-primary csv">Export CSV</button>
                        <button type="button" class="btn btn-sm btn-primary sql">Export SQL</button>
                        <button type="button" class="btn btn-sm btn-primary txt">Export TXT</button>
                        <button type="button" class="btn btn-sm btn-primary json">Export JSON</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDealModal" tabindex="-1" aria-labelledby="editDealModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDealModalLabel">Edit Deal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('deals.update') }}" method="POST">
                        @csrf <!-- CSRF Token -->
                        <input type="hidden" id="dealId" name="deal_id">

                        <div class="mb-3">
                            <label for="dealName" class="form-label">Deal Name</label>
                            <input type="text" class="form-control" id="dealName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="dealDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="dealDescription" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="dealStatus" class="form-label">Status</label>
                            <select class="form-select" id="dealStatus" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.dltBtn').click(function(e) {
                var form = $(this).closest('form');
                var dataID = $(this).data('id');
                // alert(dataID);
                e.preventDefault();
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this data!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            form.submit();
                        } else {
                            swal("Your data is safe!");
                        }
                    });
            })
        })


        function copyToClipboard(dealUrl) {
            const tempInput = document.createElement('input');
            tempInput.value = dealUrl; // Assign the Google Drive folder URL to the input
            document.body.appendChild(tempInput);

            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');

            document.body.removeChild(tempInput);

            alert('Deal link copied to clipboard: ' + dealUrl);
        }

        function loadDealData(dealId) {

            $.ajax({
                url: '/deals/' + dealId + '/edit',
                method: 'GET',
                success: function(response) {
                    // Populate the modal fields
                    $('#dealId').val(response.id);
                    $('#dealName').val(response.name);
                    $('#dealDescription').val(response.description);
                    $('#dealStatus').val(response.status.toString());
                },
                error: function(xhr) {
                    alert('Failed to load deal details.');
                }
            });
        }
    </script>
@endsection
