<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Acquisight | Acquisight - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->

    <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ url('assets/css/jsvectormap.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    <!-- App css -->
    <link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- Top Bar Start -->
    <div class="topbar d-print-none">
        <div class="container-xxl">
            <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">
                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    <li>
                        <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                            <i class="iconoir-menu-scale"></i>
                        </button>
                    </li>
                    <h3 class="mb-0 fw-bold text-truncate">Welcome Back</h3>

                </ul>
                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    {{-- <li class="topbar-item">
                        <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                            <i class="icofont-moon dark-mode"></i>
                            <i class="icofont-sun light-mode"></i>
                        </a>
                    </li> --}}

                    <li class="dropdown topbar-item">
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="{{ url('assets/images/user-avatar.jpg') }}" alt=""
                                class="thumb-lg rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end py-0">
                            <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                <div class="flex-shrink-0">
                                    <img src="{{ url('assets/images/user-avatar.jpg') }}" alt=""
                                        class="thumb-md rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                    <h6 class="my-0 fw-medium text-dark fs-13">
                                        John Doe
                                    </h6>
                                    <small class="text-muted mb-0">Broker</small>
                                </div><!--end media-body-->
                            </div>
                            <div class="dropdown-divider mt-0"></div>
                            <small class="text-muted px-2 pb-1 d-block">Account</small>
                            <a class="dropdown-item" href="{{ route('user.profile.view', ['id' => auth()->id()]) }}"><i
                                    class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>
                            <div class="dropdown-divider mb-0"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul><!--end topbar-nav-->
            </nav>
            <!-- end navbar-->
        </div>
    </div>
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <div class="startbar d-print-none">
        <!--start brand-->
        <div class="brand">
            <a href="{{ auth()->check() && auth()->user()->roles_id == 1 ? route('admin.dashboard') : (auth()->check() && auth()->user()->roles_id == 4 ? 'javascript:void(0);' : route('seller.index')) }}"
                class="logo">
                <span>
                    <img src="{{ url('assets/images/admin-logo.jpg') }}" alt="logo" class="logo-sm"
                        style="width:150px; height:40px; margin-right:50px;">
                </span>
            </a>
        </div>
        <!--end brand-->
        <!--start startbar-menu-->
        <div class="startbar-menu">
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                <div class="d-flex align-items-start flex-column w-100">
                    <!-- Navigation -->
                    <ul class="navbar-nav mb-auto w-100">
                        <li class="menu-label pt-0 mt-0">
                            <span>Main Menu</span>
                        </li>

                        {{-- Deals Section --}}
                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarDeals" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarDeals">
                                <i class="iconoir-view-grid menu-icon"></i>
                                <span>Deals</span>
                            </a>
                            <div class="collapse" id="sidebarDeals">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        @if (auth()->check() && auth()->user()->roles_id == 4)
                                            <a class="nav-link"
                                                href="{{ route('buyer.detail.show', ['id' => auth()->user()->dealInvitation->deal_id ?? 0]) }}">
                                                Deals
                                            </a>
                                        @elseif(auth()->check() && auth()->user()->roles_id == 2)
                                            <a class="nav-link" href="{{ route('broker.index') }}">Deals</a>
                                        @else
                                            <a class="nav-link" href="{{ route('seller.index') }}">Deals</a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- Requests Section (Only for Role ID 2) --}}
                        @if (auth()->check() && auth()->user()->roles_id == 2)
                            <li class="nav-item">
                                <a class="nav-link" href="#sidebarRequests" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarRequests">
                                    <i class="iconoir-view-grid menu-icon"></i>
                                    <span>Requests</span>
                                </a>
                                <div class="collapse" id="sidebarRequests">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('broker.request') }}">Requests</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <!--end startbar-menu-->
    </div><!--end startbar-->
    <div class="startbar-overlay d-print-none"></div>
    <!-- end leftbar-tab-menu-->

    <div class="page-wrapper">
        <div class="page-content">
            @yield('user-dasboard-content')
            @yield('seller-deals-index-content')
            @yield('seller-detail-content')
            @yield('seller-deals-view-content')
            @yield('seller-files-index-content')
            @yield('buyer-detail-content')
            @yield('buyer-deals-view-content')
            @yield('buyer-files-index-content')
            @yield('user-profile-content')
            @yield('broker-deals-index-content')
            @yield('broker-detail-content')
            @yield('broker-deals-view-content')
            @yield('broker-files-index-content')
            @yield('broker-request-content')
            
            <footer class="footer text-center text-sm-start d-print-none">
                <div class="container-xxl">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0 rounded-bottom-0">
                                <div class="card-body">
                                    <p class="text-muted mb-0">
                                        ©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script>
                                        Acquisight
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>


    <!-- end page-wrapper -->

    <!-- Javascript  -->
    <!-- vendor js -->

    <script src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ url('assets/js/simple-datatables.js') }}"></script>
    <script src="{{ url('assets/js/datatable.init.js') }}"></script>
    <script src="{{ url('assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ url('assets/js/stock-prices.js') }}"></script>
    <script src="{{ url('assets/js/jsvectormap.min.js') }}"></script>
    <script src="{{ url('assets/js/world.js') }}"></script>
    <script src="{{ url('assets/js/index.init.js') }}"></script>
    <script src="{{ url('assets/js/app.js') }}"></script>

</body>
<!--end body-->

</html>
