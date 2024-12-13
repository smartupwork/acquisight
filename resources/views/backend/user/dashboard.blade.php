@extends('backend.user.layout')
@section('user-dasboard-content')
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold fs-14">Users</p>
                                <h3 class="mt-2 mb-0 fw-bold">11</h3>
                            </div>
                            <!--end col-->
                            <div class="col-3 align-self-center">
                                <div
                                    class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                    <i class="iconoir-hexagon-dice h1 align-self-center mb-0 text-secondary"></i>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        <p class="mb-0 text-truncate text-muted mt-3"><span class="text-success">8.5%</span>
                            New Users Today</p>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold fs-14">Deals</p>
                                <h3 class="mt-2 mb-0 fw-bold">18</h3>
                            </div>
                            <!--end col-->
                            <div class="col-3 align-self-center">
                                <div
                                    class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                    <i class="iconoir-clock h1 align-self-center mb-0 text-secondary"></i>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        <p class="mb-0 text-truncate text-muted mt-3"><span class="text-success">1.5%</span>
                            Weekly Avg Deals</p>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold fs-14">Contacts</p>
                                <h3 class="mt-2 mb-0 fw-bold">231</h3>
                            </div>
                            <!--end col-->
                            <div class="col-3 align-self-center">
                                <div
                                    class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                    <i class="iconoir-percentage-circle h1 align-self-center mb-0 text-secondary"></i>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        <p class="mb-0 text-truncate text-muted mt-3"><span class="text-danger">8%</span>
                            Contacts Rate Weekly</p>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div><!-- container -->
@endsection
