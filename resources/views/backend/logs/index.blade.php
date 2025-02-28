@extends('backend.admin.layout')
@section('admin-log-view-content')
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="clearfix">
                    <ul class="nav nav-tabs my-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fw-semibold py-2 active" data-bs-toggle="tab" href="#documents" role="tab"
                                aria-selected="true">
                                <span class="">View File Logs</span></a>
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
                                                <th class="border-top-0">User Name</th>
                                                <th class="border-top-0">Email</th>
                                                <th class="border-top-0">Deal</th>
                                                <th class="border-top-0">Sub Folder</th>
                                                <th class="border-top-0">File Name</th>
                                                <th class="border-top-0">Viewed At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($files as $file)
                                                <tr>
                                                    <td>
                                                        {{ $file->user?->name ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $file->user?->email ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $file->dealFile->deal?->gcs_deal_id ?? 'N/A' }}</td>
                                                    <td>{{ $file->dealFile->dealFolder?->folder_name ?? 'N/A' }}</td>
                                                    <td>{{ $file->dealFile?->file_name ?? 'N/A' }}</td>
                                                    <td>{{ $file->viewed_at ?? 'N/A' }}</td>
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
        </div>
    </div>
@endsection
