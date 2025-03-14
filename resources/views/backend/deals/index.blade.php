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
                        <input type="hidden" id="deal_meta_id" name="deal_meta_id">

                        <div class="mb-3">
                            <label for="dealName" class="form-label">Deal Name</label>
                            <input type="text" class="form-control" id="dealName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="dealDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="dealDescription" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="asking_price" class="form-label">Asking Price</label>
                            <input type="text" class="form-control" id="asking_price" name="asking_price" required>
                        </div>
                        <div class="mb-3">
                            <label for="gross_revenue" class="form-label">Gross Revenue Last Year</label>
                            <input type="text" class="form-control" id="gross_revenue" name="gross_revenue" required>
                        </div>
                        <div class="mb-3">
                            <label for="cash_flow" class="form-label">Cash Flow</label>
                            <input type="text" class="form-control" id="cash_flow" name="cash_flow" required>
                        </div>
                        <div class="mb-3">
                            <label for="ebitda" class="form-label">EBITDA Adjusted EBITDA (SDE)</label>
                            <input type="text" class="form-control" id="ebitda" name="ebitda" required>
                        </div>
                        <div class="mb-3">
                            <label for="inventory" class="form-label">Inventory</label>
                            <input type="text" class="form-control" id="inventory" name="inventory" required>
                        </div>
                        <div class="mb-3">
                            <label for="ffe" class="form-label">FF&E</label>
                            <input type="text" class="form-control" id="ffe" name="ffe" required>
                        </div>
                        <div class="mb-3">
                            <label for="business_desc" class="form-label">Business Description</label>
                            <input type="text" class="form-control" id="business_desc" name="business_desc" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_employee" class="form-label">Number of Employees</label>
                            <input type="text" class="form-control" id="no_employee" name="no_employee" required>
                        </div>
                        <div class="mb-3">
                            <label for="real_estate" class="form-label">Real Estate</label>
                            <input type="text" class="form-control" id="real_estate" name="real_estate" required>
                        </div>
                        <div class="mb-3">
                            <label for="rent" class="form-label">Rent</label>
                            <input type="text" class="form-control" id="rent" name="rent" required>
                        </div>
                        <div class="mb-3">
                            <label for="lease_expiration" class="form-label">Lease Expiration</label>
                            <input type="text" class="form-control" id="lease_expiration" name="lease_expiration" required>
                        </div>
                        <div class="mb-3">
                            <label for="facilities" class="form-label">Facilities</label>
                            <input type="text" class="form-control" id="facilities" name="facilities" required>
                        </div>
                        <div class="mb-3">
                            <label for="market_outlook" class="form-label">Market Outlook/ Competition</label>
                            <input type="text" class="form-control" id="market_outlook" name="market_outlook" required>
                        </div>
                        <div class="mb-3">
                            <label for="selling_reason" class="form-label">Reason For Selling</label>
                            <input type="text" class="form-control" id="selling_reason" name="selling_reason" required>
                        </div>
                        <div class="mb-3">
                            <label for="train_support" class="form-label">Training/Support</label>
                            <input type="text" class="form-control" id="train_support" name="train_support" required>
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

                    $('#dealId').val(response.id);
                    $('#deal_meta_id').val(response.deal_meta_id);
                    $('#dealName').val(response.name);
                    $('#dealDescription').val(response.description);
                    $('#asking_price').val(response.asking_price);
                    $('#gross_revenue').val(response.gross_revenue);
                    $('#cash_flow').val(response.cash_flow);
                    $('#ebitda').val(response.ebitda);
                    $('#inventory').val(response.inventory);
                    $('#ffe').val(response.ffe);
                    $('#business_des').val(response.business_des);
                    $('#location').val(response.location);
                    $('#no_employee').val(response.no_employee);
                    $('#real_estate').val(response.real_estate);
                    $('#rent').val(response.rent);
                    $('#lease_expiration').val(response.lease_expiration);
                    $('#facilities').val(response.facilities);
                    $('#market_outlook').val(response.market_outlook);
                    $('#selling_reason').val(response.selling_reason);
                    $('#train_support').val(response.train_support);
                    $('#dealStatus').val(response.status.toString());
                },
                error: function(xhr) {
                    alert('Failed to load deal details.');
                }
            });
        }
    </script>
@endsection
