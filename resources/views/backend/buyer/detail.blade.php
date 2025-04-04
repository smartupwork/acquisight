@extends('backend.user.layout')
@section('buyer-detail-content')
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
        @php
            $dealRequest = \App\Models\DealRequest::where('deal_id', $deal->id)
                ->where('user_id', auth()->id())
                ->where('status', 'approved') // Check if the request is approved
                ->first();
        @endphp
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
                                    @if ($dealRequest)
                                        <!-- If the request is approved, show the View Files button -->
                                        <a href="{{ route('buyer.deals.view', $deal->id) }}" type="button"
                                            class="btn btn-primary">View Files</a>
                                    @else
                                        <!-- If request is not approved, show the request form -->
                                        <form action="{{ route('buyer.deals.request', $deal->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Request File Access</button>
                                        </form>
                                    @endif
                                </div>
                                {{-- <div class="input-group">
                                    <input type="text" class="form-control" id="clipboardInput"
                                        value="{{ route('buyerregistration.register') }}" readonly>
                                    <button class="btn btn-secondary" type="button" id="copyButton">
                                        <i class="far fa-copy me-2"></i> Copy
                                    </button>
                                </div> --}}
                            </div>
                            <div class="col-lg-5 offset-lg-1 text-center">
                                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                @php
                                                    $filePath = $deal->deal_image ?? 'assets/images/default.png';
                                                    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                                    $videoExtensions = [
                                                        'mp4',
                                                        'avi',
                                                        'mov',
                                                        'wmv',
                                                        'mkv',
                                                        'flv',
                                                        'webm',
                                                        '3gp',
                                                    ]; // Supported video formats
                                                @endphp

                                                @if (in_array($extension, $videoExtensions))
                                                    <video class="d-block w-100" controls>
                                                        <source src="{{ asset($filePath) }}"
                                                            type="video/{{ $extension == '3gp' ? '3gpp' : $extension }}">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                    <img src="{{ asset($filePath) }}" class="d-block w-100"
                                                        alt="Deal Image">
                                                @endif
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
                            <ul class="list-group asking-price">
                                <li class="list-group-item   align-items-center">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div>
                                                Asking Price:
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
    
                                            <span class="text badge-pill">{{ $dealMeta->asking_price ?? 'N/A' }}</span>
                                        </div>
                                    </div>
    
    
                                </li>
                                <li class="list-group-item   align-items-center">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div>
                                                Gross Revenue:
                                            </div>
                                        </div>
                                        <div class="col-sm-9"><span
                                                class="text badge-pill">{{ $dealMeta->gross_revenue ?? 'N/A' }}</span></div>
                                    </div>
    
    
                                </li>
                                <li class="list-group-item   align-items-center">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div>
                                                Cash Flow:
                                            </div>
                                        </div>
                                        <div class="col-sm-9"><span
                                                class="text badge-pill">{{ $dealMeta->cash_flow ?? 'N/A' }}</span></div>
                                    </div>
                                </li>
                                <li class="list-group-item   align-items-center">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div>
                                                EBITDA:
                                            </div>
                                        </div>
                                        <div class="col-sm-9"><span class="text">{{ $dealMeta->ebitda ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item  align-items-center">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div>
                                                Inventory:
                                            </div>
                                        </div>
                                        <div class="col-sm-9"> <span
                                                class="text badge-pill">{{ $dealMeta->inventory ?? 'N/A' }}</span></div>
                                    </div>
                                </li>
                                <li class="list-group-item   align-items-center">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div>
                                                FF&E:
                                            </div>
                                        </div>
                                        <div class="col-sm-9"><span
                                                class="text badge-pill">{{ $dealMeta->ffe ?? 'N/A' }}</span></div>
                                    </div>
    
                                </li>
                            </ul>
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
                                <li class="list-group-item justify-content-between align-items-center">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div>
                                                Reason For Selling:
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <span class="text badge-pill">{{ $dealMeta->selling_reason ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item justify-content-between align-items-center">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div>
                                                Training/Support:
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <span class="text badge-pill">{{ $dealMeta->train_support ?? 'N/A' }}</span>
                                        </div>
                                    </div>
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
