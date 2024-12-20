@extends('auth.layout')
@section('register-content')
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
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Let's Get Started</h4>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <form class="my-4" method="POST" action="{{ route('registerIn') }}">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="username">Name</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                placeholder="Enter Name">
                                        </div>
                                        @error('username')
                                            <span>{{ $message }}</span>
                                        @enderror
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
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
                                        <div class="form-group mb-2">
                                            <label for="horizontalInput1" class="form-label">Role</label>
                                                <select class="form-select" name="role" aria-label="Default select example">
                                                    <option selected="">Select Role</option>
                                                    <option value="2" >Broker</option>
                                                    <option value="3" >Seller</option>
                                                    <option value="4" >Buyer</option>
                                                    <option value="5" >Employee</option>
                                                </select>
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
                                        </div> <!--end form-group-->
                                    </form><!--end form-->
                                    <div class="text-center mb-2">
                                        <p class="text-muted">Already have an account ?
                                            <a href="{{ route('login-view') }}" class="text-primary ms-2">Login</a>
                                        </p>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
@endsection
