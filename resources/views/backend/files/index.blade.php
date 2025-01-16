@extends('backend.admin.layout')
@section('admin-files-index-content')
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="clearfix">
                    <ul class="nav nav-tabs my-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fw-semibold py-2 active" data-bs-toggle="tab" href="#documents" role="tab"
                                aria-selected="true"><i class="fa-regular fa-folder-open me-1"></i> Test
                                <span class="badge rounded text-blue bg-blue-subtle ms-1">32</span></a>
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="documents" role="tabpanel">
                                <div class="table-responsive browser_users">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-top-0">Folder Name</th>
                                                <th class="border-top-0 text-end">Last Modified</th>
                                                {{-- <th class="border-top-0 text-end">Size</th> --}}
                                                <th class="border-top-0 text-end">Action</th>
                                            </tr><!--end tr-->
                                        </thead>
                                        <tbody>
                                            @if (!empty($files))
                                                @foreach ($files as $file)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-inline-flex justify-content-center align-items-center thumb-md bg-blue-subtle rounded mx-auto me-1">
                                                                <i class="fa-regular fa-file me-1 text-blue"></i>
                                                            </div>
                                                            <a href="{{ $file['file_path'] }}" target="_blank" class="text-body">{{ $file['file_name'] }}</a>
                                                        </td>
                                                        <td class="text-end">{{ $file['updated_at'] }}</td>
                                                        {{-- <td class="text-end">{{ $file['size'] }}</td> --}}
                                                        <td class="text-end">
                                                            <a href="#"><i
                                                                    class="las la-download text-secondary fs-18"></i></a>
                                                            <a href="#"><i
                                                                    class="las la-pen text-secondary fs-18"></i></a>
                                                            <a href="#"><i
                                                                    class="las la-trash-alt text-secondary fs-18"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5">No files found for this deal.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
        </div>
    </div>
@endsection
