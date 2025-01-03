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
                        {{-- <li class="nav-item" role="presentation">
                            <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#images" role="tab"
                                aria-selected="false" tabindex="-1"><i class="fa-regular fa-image me-1"></i> Images <span
                                    class="badge rounded text-blue bg-blue-subtle ms-1">85</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#audio" role="tab"
                                aria-selected="false" tabindex="-1"><i class="fa-solid fa-headphones me-1"></i> Audio <span
                                    class="badge rounded text-blue bg-blue-subtle ms-1">21</span></a>
                        </li> --}}
                    </ul>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">{{ $folderName }}</h4>
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
                                            @if (!empty($files))
                                                @foreach ($files as $file)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-inline-flex justify-content-center align-items-center thumb-md bg-blue-subtle rounded mx-auto me-1">
                                                                <i class="fa-regular fa-file me-1 text-blue"></i>
                                                            </div>
                                                            <a href="{{ asset('deals_main/' . $deal->name . '_' . $deal->id . '/' . $folderName . '/' . $file['name']) }}" target="_blank" class="text-body">{{ $file['name'] }}</a>
                                                        </td>
                                                        <td class="text-end">{{ $file['last_modified'] }}</td>
                                                        <td class="text-end">{{ $file['size'] }}</td>
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
                            <div class="tab-pane" id="images" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-top-0">Name</th>
                                                <th class="border-top-0 text-end">Last Modified</th>
                                                <th class="border-top-0 text-end">Size</th>
                                                <th class="border-top-0 text-end">Members</th>
                                                <th class="border-top-0 text-end">Action</th>
                                            </tr><!--end tr-->
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-danger-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-image fs-18 align-self-center mb-0 text-danger"></i>
                                                    </div>
                                                    <a href="#" class="text-body">img52315.jpeg</a>
                                                </td>
                                                <td class="text-end">18 Jul 2024</td>
                                                <td class="text-end"> 2.3 MB</td>
                                                <td class="text-end">
                                                    <div class="img-group d-flex justify-content-end">
                                                        <a class="user-avatar position-relative d-inline-block"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-2.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                        <a class="user-avatar position-relative d-inline-block ms-n2"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-5.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                        <a class="user-avatar position-relative d-inline-block ms-n2"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-3.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-danger-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-image fs-18 align-self-center mb-0 text-danger"></i>
                                                    </div>
                                                    <a href="#" class="text-body">img63695.jpeg</a>
                                                </td>
                                                <td class="text-end">08 Dec 2024</td>
                                                <td class="text-end"> 3.7 MB</td>
                                                <td class="text-end">
                                                    <div class="img-group d-flex justify-content-end">
                                                        <a class="user-avatar position-relative d-inline-block"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-3.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                        <a class="user-avatar position-relative d-inline-block ms-n2"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-10.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-danger-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-image fs-18 align-self-center mb-0 text-danger"></i>
                                                    </div>
                                                    <a href="#" class="text-body">img00021.jpeg</a>
                                                </td>
                                                <td class="text-end">30 Nov 2024</td>
                                                <td class="text-end"> 1.5 MB</td>
                                                <td class="text-end">
                                                    <div class="img-group d-flex justify-content-end">
                                                        <a class="user-avatar position-relative d-inline-block"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-7.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                        <a class="user-avatar position-relative d-inline-block ms-n2"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-2.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-danger-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-image fs-18 align-self-center mb-0 text-danger"></i>
                                                    </div>
                                                    <a href="#" class="text-body">img36251.jpeg</a>
                                                </td>
                                                <td class="text-end">09 Sep 2024</td>
                                                <td class="text-end"> 3.2 MB</td>
                                                <td class="text-end">
                                                    -
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-danger-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-image fs-18 align-self-center mb-0 text-danger"></i>
                                                    </div>
                                                    <a href="#" class="text-body">img362511.jpeg</a>
                                                </td>
                                                <td class="text-end">14 Aug 2024</td>
                                                <td class="text-end"> 12.7 MB</td>
                                                <td class="text-end">
                                                    <div class="img-group d-flex justify-content-end">
                                                        <a class="user-avatar position-relative d-inline-block"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-2.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                        <a class="user-avatar position-relative d-inline-block ms-n2"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-3.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                        <a class="user-avatar position-relative d-inline-block ms-n2"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-8.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-danger-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-image fs-18 align-self-center mb-0 text-danger"></i>
                                                    </div>
                                                    <a href="#" class="text-body">img963852.jpeg</a>
                                                </td>
                                                <td class="text-end">12 Aug 2024</td>
                                                <td class="text-end"> 5.2 MB</td>
                                                <td class="text-end">
                                                    <div class="img-group d-flex justify-content-end">
                                                        <a class="user-avatar position-relative d-inline-block"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-1.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                        <a class="user-avatar position-relative d-inline-block ms-n2"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-4.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                        <a class="user-avatar position-relative d-inline-block ms-n2"
                                                            href="#">
                                                            <img src="assets/images/users/avatar-6.jpg" alt="avatar"
                                                                class="thumb-md shadow-sm rounded-circle">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                        </tbody>
                                    </table> <!--end table-->
                                </div><!--end /div-->
                            </div>
                            <div class="tab-pane" id="audio" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-top-0">Name</th>
                                                <th class="border-top-0 text-end">Last Modified</th>
                                                <th class="border-top-0 text-end">Size</th>
                                                <th class="border-top-0 text-end">Action</th>
                                            </tr><!--end tr-->
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-secondary-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-microphone fs-18 align-self-center mb-0 text-secondary"></i>
                                                    </div>
                                                    <a href="#" class="text-body">audio52315..</a>
                                                </td>
                                                <td class="text-end">18 Jul 2024</td>
                                                <td class="text-end"> 2.3 MB</td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-secondary-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-microphone fs-18 align-self-center mb-0 text-secondary"></i>
                                                    </div>
                                                    <a href="#" class="text-body">audio63695..</a>
                                                </td>
                                                <td class="text-end">08 Dec 2024</td>
                                                <td class="text-end"> 3.7 MB</td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-secondary-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-microphone fs-18 align-self-center mb-0 text-secondary"></i>
                                                    </div>
                                                    <a href="#" class="text-body">audio00021..</a>
                                                </td>
                                                <td class="text-end">30 Nov 2024</td>
                                                <td class="text-end"> 1.5 MB</td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-secondary-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-microphone fs-18 align-self-center mb-0 text-secondary"></i>
                                                    </div>
                                                    <a href="#" class="text-body">audio36251..</a>
                                                </td>
                                                <td class="text-end">09 Sep 2024</td>
                                                <td class="text-end"> 3.2 MB</td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-secondary-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-microphone fs-18 align-self-center mb-0 text-secondary"></i>
                                                    </div>
                                                    <a href="#" class="text-body">audio362511..</a>
                                                </td>
                                                <td class="text-end">14 Aug 2024</td>
                                                <td class="text-end"> 12.7 MB</td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                            <tr>
                                                <td>
                                                    <div
                                                        class="d-inline-flex justify-content-center align-items-center thumb-md bg-secondary-subtle rounded mx-auto me-1">
                                                        <i
                                                            class="fa-solid fa-microphone fs-18 align-self-center mb-0 text-secondary"></i>
                                                    </div>
                                                    <a href="#" class="text-body">audio963852..</a>
                                                </td>
                                                <td class="text-end">12 Aug 2024</td>
                                                <td class="text-end"> 5.2 MB</td>
                                                <td class="text-end">
                                                    <a href="#"><i
                                                            class="las la-download text-secondary fs-18"></i></a>
                                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                    <a href="#"><i
                                                            class="las la-trash-alt text-secondary fs-18"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                        </tbody>
                                    </table> <!--end table-->
                                </div><!--end /div-->
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
        </div>
    </div>
@endsection
