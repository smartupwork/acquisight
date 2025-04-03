@extends('backend.user.layout')
@section('buyer-files-index-content')
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="clearfix">
                    <ul class="nav nav-tabs my-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fw-semibold py-2 active" data-bs-toggle="tab" href="#documents" role="tab"
                                aria-selected="true"><i class="fa-regular fa-folder-open me-1"></i> Files
                            </a>
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
                                    <table class="table datatable" id="datatable_2">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-top-0">File Name</th>
                                                <th class="border-top-0">Last Modified</th>
                                            </tr>
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
                                                            {{-- <a href="#" class="preview-link text-body"
                                                                onclick="previewFile('{{ $file->signed_url }}', '{{ $file->mime_type }}')">
                                                                {{ $file->file_name }}
                                                            </a> --}}
                                                            <a href="#" class="preview-link text-body"
                                                                onclick="previewFile('{{ $file->signed_url }}', '{{ $file->mime_type }}', '{{ $file->id }}', '{{ $file->file_name }}')">
                                                                {{ $file->file_name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $file['created_at'] }}</td>
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
    <div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title">File Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="fileViewerContainer">
                        <iframe id="fileViewer" style="width:100%; height:500px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


    <script>
        function previewFile(fileUrl, fileType, fileId, fileName) {

            $.ajax({
                url: '/log-file-view',
                method: 'POST',
                data: {
                    file_id: fileId,
                    file_name: fileName,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function() {
                    console.log('Failed to log file view.');
                }
            });

            var container = $('#fileViewerContainer');
            var fileExtension = fileUrl.split('.').pop().toLowerCase();

            // Reset viewer
            container.html('');

            // Handle empty/missing files
            if (!fileUrl || !fileType) {
                container.html('<p class="text-danger">⚠️ This file is empty or unavailable.</p>');
            }
            // Image Files (PNG, JPEG, GIF, WebP, SVG, BMP, etc.)
            else if (fileType.startsWith('image/')) {
                container.html(`<img src="${fileUrl}" class="img-fluid" style="max-height: 500px; width: auto;">`);
            }
            // PDF Files
            else if (fileType === 'application/pdf') {
                container.html(`<iframe src="${fileUrl}" width="100%" height="500px"></iframe>`);
            }
            // Video Files (MP4, WebM, OGG, AVI, MOV)
            else if (fileType.startsWith('video/')) {
                container.html(`
            <video controls width="100%">
                <source src="${fileUrl}" type="${fileType}">
                Your browser does not support the video tag.
            </video>
        `);
            }
            // Audio Files (MP3, WAV, OGG)
            else if (fileType.startsWith('audio/')) {
                container.html(`
            <audio controls>
                <source src="${fileUrl}" type="${fileType}">
                Your browser does not support the audio tag.
            </audio>
        `);
            }
            // Text-Based Files (TXT, CSV, JSON, XML, LOG, HTML)
            else if (['text/plain', 'text/csv', 'application/json', 'application/xml', 'text/xml', 'text/html'].includes(
                    fileType)) {
                fetch(fileUrl)
                    .then(response => response.text())
                    .then(text => {
                        container.html(
                            `<pre style="white-space: pre-wrap; word-wrap: break-word; padding: 10px;">${text}</pre>`
                        );
                    })
                    .catch(error => {
                        container.html('<p class="text-danger">⚠️ Unable to preview this text file.</p>');
                    });
            }
            // Microsoft Office Files (DOC, DOCX, XLS, XLSX, PPT, PPTX)
            else if ([
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                ].includes(fileType)) {

                // Check if the file is public (Google Docs Viewer requires public URLs)
                if (fileUrl.startsWith('http')) {
                    container.html(`
                <iframe src="https://docs.google.com/gview?url=${encodeURIComponent(fileUrl)}&embedded=true" 
                        width="100%" height="500px"></iframe>
            `);
                } else {
                    container.html(
                        `<p class="text-danger">⚠️ This Office document cannot be previewed. Please download it.</p>`);
                }
            }
            // Unsupported File Types
            else {
                container.html(`
            <p class="text-danger">
                ⚠️ Preview not available for this file type. 
                <a href="${fileUrl}" download>Click here to download</a>.
            </p>
        `);
            }

            // Show the modal
            $('#filePreviewModal').modal('show');
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.close').click(function() {
                $('#filePreviewModal').modal('hide');
            });
        });
    </script>

@endsection
