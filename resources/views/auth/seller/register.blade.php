@extends('auth.layout')
@section('seller-register-content')
   {{-- seller registeration blade --}}
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="index.html" class="logo logo-admin">
                                            <img src="{{ url('assets/images/admin-logo.svg') }}" height="50"
                                                alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Let's Get Started as Seller</h4>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <form class="my-4" method="POST" action="{{ route('seller.register.submit') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}" />
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter Name">
                                        </div>
                                        @error('name')
                                            <span>{{ $message }}</span>
                                        @enderror
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email" value="{{ $email }}"
                                                placeholder="Enter email">
                                        </div>
                                        @error('email')
                                            <span>{{ $message }}</span>
                                        @enderror

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password">
                                        </div>
                                        @error('password') <span>{{ $message }}</span> @enderror
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="userpassword">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation" id="userpassword" placeholder="Confirm password">
                                        </div>
                                        <div class="form-group row mt-3">
                                        </div><!--end form-group-->
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit">Register <i
                                                            class="fas fa-sign-in-alt ms-1"></i>
                                                    </button>
                                                </div>
                                            </div><!--end col-->
                                        </div>
                                        
                                        <div class="form-group row mt-3">
                                            <div class="col-12">
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input" type="checkbox" id="customSwitchSuccess">
                                                    <label class="form-check-label" for="customSwitchSuccess">By registering you agree to the<a href="#" class="text-primary">Terms of Use</a></label>
                                                </div>
                                            </div><!--end col--> 
                                        </div><!--end form-group-->
                                    </form><!--end form-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
@endsection
