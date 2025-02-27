@extends('backend.admin.layout')
@section('admin-profile-content')
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                <div class="d-flex align-items-center flex-row flex-wrap">
                                    <div class="position-relative me-3">
                                        <img src="assets/images/users/avatar-7.jpg" alt="" height="120"
                                            class="rounded-circle">
                                        <a href="#"
                                            class="thumb-md justify-content-center d-flex align-items-center bg-primary text-white rounded-circle position-absolute end-0 bottom-0 border border-3 border-card-bg">
                                            <i class="fas fa-camera"></i>
                                        </a>
                                    </div>
                                    <div class="">
                                        <h5 class="fw-semibold fs-22 mb-1">{{ $user->name }}</h5>
                                        <p class="mb-0 text-muted fw-medium">{{ $user->role?->name ?? 'No Role Assigned' }}
                                        </p>
                                    </div>
                                </div>
                            </div><!--end col-->

                        </div><!--end row-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
        </div><!--end row-->

        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Overview</h4>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        {{-- <p class="text-muted fw-medium mb-3">It is a long established fact that a reader will be distracted
                            by the readable content of a page when looking at its layout.</p> --}}

                        <ul class="list-unstyled mb-0">
                            <li class=""><i class="las la-birthday-cake me-2 text-secondary fs-22 align-middle"></i>
                                <b> Birth Date </b> : {{ $user->dob ?? 'Add Date of Birth' }}
                            </li>
                            <li class="mt-2"><i class="las la-briefcase me-2 text-secondary fs-22 align-middle"></i> <b>
                                    Role </b> : {{ $user->role?->name ?? 'No Role Assigned' }}</li>
                            <li class="mt-2"><i class="las la-university me-2 text-secondary fs-22 align-middle"></i>
                                <b> Address </b> : {{ $user->address ?? 'Add Address' }}
                            </li>
                            <li class="mt-2"><i class="las la-phone me-2 text-secondary fs-22 align-middle"></i> <b>
                                    Phone </b> : {{ $user->phone ?? 'Add Phone' }}</li>
                            <li class="mt-2"><i class="las la-envelope text-secondary fs-22 align-middle me-2"></i> <b>
                                    Email </b> : {{ $user->email }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#settings" role="tab"
                            aria-selected="true">Settings</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane p-3 active show" id="settings" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Personal Information</h4>
                                    </div><!--end col-->
                                </div> <!--end row-->
                            </div><!--end card-header-->
                            <form method="POST" action="{{ route('profile.submit') }}">
                                @csrf
                                <div class="card-body pt-0">
                                    <!-- Name Field -->
                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Name</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="text" name="name"
                                                value="{{ old('name', $user->name) }}">
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone Field -->
                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Contact
                                            Phone</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="las la-phone"></i></span>
                                                <input type="text" class="form-control" name="phone"
                                                    value="{{ old('phone', $user->phone) }}" placeholder="Phone">
                                            </div>
                                            @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email Field -->
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Email
                                            Address</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="las la-at"></i></span>
                                                <input type="text" class="form-control" name="email"
                                                    value="{{ old('email', $user->email) }}" placeholder="Email">
                                            </div>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Date of Birth Field -->
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Date
                                            of Birth</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <input class="form-control" name="dob" type="date"
                                                    value="{{ old('dob', $user->dob) }}" id="example-date-input">
                                            </div>
                                            @error('dob')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Address Field -->
                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Address</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <textarea class="form-control" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                            </div>
                                            @error('address')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit and Cancel Buttons -->
                                    <div class="form-group row">
                                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <button type="button" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div><!--end card-->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Change Password</h4>
                            </div><!--end card-header-->
                            <form method="POST" action="{{ route('profile.change-password') }}">
                                @csrf
                                <div class="card-body pt-0">
                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Current
                                            Password</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" name="current_password"
                                                placeholder="Current Password" required>
                                            @error('current_password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">New
                                            Password</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" name="new_password"
                                                placeholder="New Password" required>
                                            @error('new_password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Confirm
                                            Password</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" name="new_password_confirmation"
                                                placeholder="Confirm Password" required>
                                            @error('new_password_confirmation')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                            <button type="button" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div><!--end card-->

                    </div>
                </div>
            </div> <!--end col-->
        </div><!--end row-->


    </div>
@endsection
