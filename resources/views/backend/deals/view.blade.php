@extends('backend.admin.layout')
@section('admin-deals-view-content')

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
                <div class="clearfix">
                    <ul class="nav nav-tabs my-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fw-semibold py-2 active" data-bs-toggle="tab" href="#documents" role="tab"
                                aria-selected="true"><i class="fa-regular fa-folder-open me-1"></i> {{ $deal->name }}
                                <span class="badge rounded text-blue bg-blue-subtle ms-1">32</span></a>
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">{{ $deal->description }}</h4>
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
                                                <th class="border-top-0 text-end">Size</th>
                                                <th class="border-top-0 text-end">Action</th>
                                            </tr><!--end tr-->
                                        </thead>
                                        <tbody>
                                            @if (!empty($folders) && is_array($folders))
                                                @foreach ($folders as $folder)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-inline-flex justify-content-center align-items-center thumb-md bg-blue-subtle rounded mx-auto me-1">
                                                                <i class="fa-regular fa-folder-open me-1 text-blue"></i>
                                                            </div>
                                                            <a href="{{ route('folders.view', ['id' => $deal->id, 'folderName' => $folder['name']]) }}"
                                                                class="text-body">{{ $folder['name'] }}</a>
                                                        </td>
                                                        <td class="text-end">{{ $folder['last_modified'] }}</td>
                                                        <td class="text-end">{{ $folder['size'] }}</td>
                                                        <td class="text-end">
                                                            <a href="#" data-toggle="modal" class="open-upload-modal" data-folder-name="{{ $folder['name'] }}" data-target="#uploadFileModal">
                                                                <i class="las la-upload text-secondary fs-18"></i>
                                                            </a>

                                                            <a href="#"><i
                                                                    class="las la-pen text-secondary fs-18"></i></a>
                                                            <a href="#"><i
                                                                    class="las la-trash-alt text-secondary fs-18"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5">No folders found for this deal.</td>
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
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFileModalLabel">Upload File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('folder.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Hidden inputs -->
                        
                        <input type="hidden" name="deal_name" value="{{ $deal->name }}" required>
                        <input type="hidden" name="folder_name" value="" required>
                        <input type="hidden" name="deal_id" value="{{ $deal->id }}" required>
    
                        <!-- File upload input -->
                        <div class="form-group">
                            <label for="fileUpload">Choose a file</label>
                            <input type="file" class="form-control" id="fileUpload" name="file" required>
                        </div>
    
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
    // When the upload icon is clicked
    $('.open-upload-modal').on('click', function () {
        // Get the folder name from the data attribute
        var folderName = $(this).data('folder-name');

        // Populate the hidden input with the folder name
        $('#uploadFileModal input[name="folder_name"]').val(folderName);
    });
});
        </script>
@endsection
