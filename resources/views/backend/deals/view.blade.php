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
                        <a class="nav-link fw-semibold py-2 active" data-bs-toggle="tab" href="#documents" role="tab"
                            aria-selected="true"><i class="fa-regular fa-folder-open me-1"></i>
                            {{ $deal->name }}
                        </a></li>
                        </li>
                    </ul>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="card-title">{{ $deal->description }}</h4>
                            </div>
                            <div class="col-md-6"">
                                <button class="btn btn-info open-folder-add-modal" style="float:right;">Add Folder</button>
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
                                                <th class="border-top-0 text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($folders) && $folders->count() > 0)
                                                @foreach ($folders as $folder)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-inline-flex justify-content-center align-items-center thumb-md bg-blue-subtle rounded mx-auto me-1">
                                                                <i class="fa-regular fa-folder-open me-1 text-blue"></i>
                                                            </div>
                                                            <a href="{{ route('deal.file.list', ['id' => $folder->id]) }}"
                                                                class="text-body">{{ $folder->folder_name }}</a>
                                                        </td>
                                                        <td class="text-end">
                                                            {{ $folder->created_at->format('Y-m-d H:i:s') }}
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="#" class="btn btn-success open-upload-modal"
                                                                data-folder-name="{{ $folder->folder_name }}"
                                                                data-folder-id="{{ $folder->id }}"
                                                                data-drive-folder-id="{{ $folder->gcs_folder_id }}">Upload
                                                            </a>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('folder.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Hidden inputs -->

                        <input type="hidden" name="folder_name" id="folder_name_input" value="">
                        <input type="hidden" name="folder_id" id="folder_id_input" value="">
                        <input type="hidden" name="drive_folder_id" id="drive_folder_id_input" value="">
                        <input type="hidden" name="deal_id" value="{{ $deal->id }}">

                        <div class="form-group">
                            <label for="fileUpload">Choose a file</label>
                            <input type="file" class="form-control" id="fileUpload" name="files[]" multiple required>
                        </div>

                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadFolderModal" tabindex="-1" aria-labelledby="uploadFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFolderModalLabel">Add New Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('new.folder.store') }}">
                        @csrf
                        <input type="hidden" name="gcs_deal_id" value="{{ $deal->gcs_deal_id }}">
                        <input type="hidden" name="deal_id" value="{{ $deal->id }}">
                        <div class="form-group">
                            <label for="folderUpload">Folder Name</label><br>
                            <input type="text" class="form-control" id="folderUpload" name="folder_name" required>
                        </div><br>

                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.open-upload-modal').on('click', function() {
                var folderName = $(this).data('folder-name');
                var folderId = $(this).data('folder-id');
                var driveFolderId = $(this).data('drive-folder-id');

                $('#folder_name_input').val(folderName);
                $('#folder_id_input').val(folderId);
                $('#drive_folder_id_input').val(driveFolderId);

                $('#uploadFileModal').modal('show');
            });
        });

        $('.open-folder-add-modal').on('click', function() {
            var folderName = $(this).data('folder-name');
            var folderId = $(this).data('folder-id');
            var driveFolderId = $(this).data('drive-folder-id');

            $('#folder_name_input').val(folderName);
            $('#folder_id_input').val(folderId);
            $('#drive_folder_id_input').val(driveFolderId);

            $('#uploadFolderModal').modal('show');
        });
    </script>
@endsection
