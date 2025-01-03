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
                                                <a href="{{ route('deals.view', $deal->id) }}" class="btn btn-success btn-sm float-left mr-1"
                                                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                    title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>
                                                <a href="" class="btn btn-primary btn-sm float-left mr-1"
                                                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                    title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('deals.inviteView', $deal->id) }}" class="btn btn-info btn-sm float-left mr-1"
                                                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                    title="invite" data-placement="bottom"><i
                                                        class="fas fa-envelope"></i></a>
                                                <form method="POST" action="{{ route('deals.destroy', [$deal->id]) }}">
                                                    @csrf
                                                    <button class="btn btn-danger btn-sm dltBtn" data-id={{ $deal->id }}
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
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
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
    </script>
@endsection
