@extends('backend.admin.layout')
@section('admin-deals-detail-content')
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
                            <div class="col-lg-5 offset-lg-1 align-self-center">
                                <div class="p-3">
                                    <span class="bg-pink-subtle p-1 rounded text-pink fw-medium">{{ $deal->name }}</span>
                                    <h3 class="my-4 font-weight-bold">Business Description</h3>
                                    <p class="fs-14 text-muted">{{ $dealMeta->business_desc ?? 'N/A' }}
                                    </p>
                                    <a href="{{ route('deals.view', $deal->id) }}" type="button"
                                        class="btn btn-primary">Get Started</a>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="clipboardInput"
                                        value="{{ route('buyerregistration.register') }}" readonly>
                                    <button class="btn btn-secondary" type="button" id="copyButton">
                                        <i class="far fa-copy me-2"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-5 offset-lg-1 text-center">

                                <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="{{ asset($deal->deal_image ?? 'assets/images/default.png') }}"
                                                class="d-block w-100" alt="Deal Image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Business Statistics</h4>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    Asking Price:
                                </div>
                                <span
                                    class="badge border border-primary text-primary badge-pill">{{ $dealMeta->asking_price ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    Gross Revenue:
                                </div>
                                <span
                                    class="badge border border-secondary text-secondary badge-pill">{{ $dealMeta->gross_revenue ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    Cash Flow:
                                </div>
                                <span
                                    class="badge border border-success text-success badge-pill">{{ $dealMeta->cash_flow ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    EBITDA:
                                </div>
                                <span
                                    class="badge border border-warning text-warning">{{ $dealMeta->ebitda ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    Inventory:
                                </div>
                                <span
                                    class="badge border border-info text-info badge-pill">{{ $dealMeta->inventory ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    FF&E:
                                </div>
                                <span
                                    class="badge border border-info text-info badge-pill">{{ $dealMeta->ffe ?? 'N/A' }}</span>
                            </li>
                        </ul><!--end list-group-->
                    </div><!--end card-body-->
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">About the Business</h4>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">

                        <dl class="row mb-0">
                            <dt class="col-sm-3">Location:</dt>
                            <dd class="col-sm-9">{{ $dealMeta->location ?? 'N/A' }}</dd>

                            <dt class="col-sm-3">Number of Employees:</dt>
                            <dd class="col-sm-9">{{ $dealMeta->no_employee ?? 'N/A' }}</dd>

                            <dt class="col-sm-3">Real Estate:</dt>
                            <dd class="col-sm-9">{{ $dealMeta->real_estate ?? 'N/A' }}</dd>

                            <dt class="col-sm-3 text-truncate">Rent:</dt>
                            <dd class="col-sm-9">{{ $dealMeta->rent ?? 'N/A' }}</dd>

                            <dt class="col-sm-3">Lease Expiration:</dt>
                            <dd class="col-sm-9">{{ $dealMeta->lease_expiration ?? 'N/A' }}</dd>

                            <dt class="col-sm-3">Facilities:</dt>
                            <dd class="col-sm-9">{{ $dealMeta->facilities ?? 'N/A' }}</dd>

                            <dt class="col-sm-3">Market Outlook/ Competition:</dt>
                            <dd class="col-sm-9">{{ $dealMeta->market_outlook ?? 'N/A' }}</dd>

                            {{-- <dt class="col-sm-3">Growth & Expansion:</dt>
                            <dd class="col-sm-9">{{ $dealMeta->business_desc ?? 'N/A' }}</dd> --}}
                        </dl>

                    </div><!--end card-body-->
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">About the Sale</h4>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    Reason For Selling:
                                </div>
                                <span
                                    class="badge border border-primary text-primary badge-pill">{{ $dealMeta->selling_reason ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    Training/Support:
                                </div>
                                <span
                                    class="badge border border-secondary text-secondary badge-pill">{{ $dealMeta->train_support ?? 'N/A' }}</span>
                            </li>
                        </ul><!--end list-group-->
                    </div><!--end card-body-->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('copyButton').addEventListener('click', function() {
            let inputField = document.getElementById('clipboardInput');
            inputField.select();
            inputField.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the input field
            document.execCommand("copy");

            // Provide user feedback
            alert("Copied: " + inputField.value);
        });
    </script>
@endsection
