@extends('backend.admin.layout')
@section('admin-deal-create-content')
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Create Deal</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form method="post" action="{{ route('deals.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label for="name" class="col-sm-2 col-form-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter Deal Name">
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="5" id="description" name="description"></textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="description" class="col-sm-2 col-form-label">Assign Broker</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="broker_email" name="broker_email" aria-label="Select a Broker" required>
                                        <option value="" selected disabled>Select a Broker</option>
                                        @foreach($brokers as $broker)
                                            <option value="{{ $broker->email }}">{{ $broker->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="deal_image" class="col-sm-2 col-form-label">Deal Image</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="deal_image" name="deal_image"
                                        placeholder="Enter Business Description">
                                    @if ($errors->has('deal_image'))
                                        <span class="text-danger">{{ $errors->first('deal_image') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="asking_price" class="col-sm-2 col-form-label">Asking Price</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="asking_price" name="asking_price"
                                        placeholder="Enter Asking Price">
                                    @if ($errors->has('asking_price'))
                                        <span class="text-danger">{{ $errors->first('asking_price') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="gross_revenue" class="col-sm-2 col-form-label">Gross Revenue Last Year</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="gross_revenue" name="gross_revenue"
                                        placeholder="Enter Gross Revenue Last Year">
                                    @if ($errors->has('gross_revenue'))
                                        <span class="text-danger">{{ $errors->first('gross_revenue') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="cash_flow" class="col-sm-2 col-form-label">Cash Flow</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="cash_flow" name="cash_flow"
                                        placeholder="Enter Cash Flow">
                                    @if ($errors->has('cash_flow'))
                                        <span class="text-danger">{{ $errors->first('cash_flow') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="ebitda" class="col-sm-2 col-form-label">EBITDA
                                    Adjusted EBITDA (SDE)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="ebitda" name="ebitda"
                                        placeholder="Enter EBITDA">
                                    @if ($errors->has('ebitda'))
                                        <span class="text-danger">{{ $errors->first('ebitda') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="inventory" class="col-sm-2 col-form-label">Inventory</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inventory" name="inventory"
                                        placeholder="Enter Inventory">
                                    @if ($errors->has('inventory'))
                                        <span class="text-danger">{{ $errors->first('inventory') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="ffe" class="col-sm-2 col-form-label">FF&E</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="ffe" name="ffe"
                                        placeholder="Enter FF&E">
                                    @if ($errors->has('ffe'))
                                        <span class="text-danger">{{ $errors->first('ffe') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="business_desc" class="col-sm-2 col-form-label">Business Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="business_desc" name="business_desc"
                                        placeholder="Enter Business Description">
                                    @if ($errors->has('business_desc'))
                                        <span class="text-danger">{{ $errors->first('business_desc') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="about_business" class="col-sm-2 col-form-label">About The Business</label>
                            </div>
                            <div class="mb-3 row">
                                <label for="location" class="col-sm-2 col-form-label">Location</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="location" name="location"
                                        placeholder="Enter Business Location">
                                    @if ($errors->has('location'))
                                        <span class="text-danger">{{ $errors->first('location') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="no_employee" class="col-sm-2 col-form-label">Number of Employees</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="no_employee" name="no_employee"
                                        placeholder="Enter Number of Employees">
                                    @if ($errors->has('no_employee'))
                                        <span class="text-danger">{{ $errors->first('no_employee') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="real_estate" class="col-sm-2 col-form-label">Real Estate</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="real_estate" name="real_estate"
                                        placeholder="Enter Real Estate">
                                    @if ($errors->has('real_estate'))
                                        <span class="text-danger">{{ $errors->first('real_estate') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="rent" class="col-sm-2 col-form-label">Rent</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="rent" name="rent"
                                        placeholder="Enter Rent">
                                    @if ($errors->has('rent'))
                                        <span class="text-danger">{{ $errors->first('rent') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="lease_expiration" class="col-sm-2 col-form-label">Lease Expiration</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="lease_expiration" name="lease_expiration"
                                        placeholder="Enter Lease Expiration">
                                    @if ($errors->has('lease_expiration'))
                                        <span class="text-danger">{{ $errors->first('lease_expiration') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="facilities" class="col-sm-2 col-form-label">Facilities</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="facilities" name="facilities"
                                        placeholder="Enter Facilities">
                                    @if ($errors->has('facilities'))
                                        <span class="text-danger">{{ $errors->first('facilities') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="market_outlook" class="col-sm-2 col-form-label">Market Outlook/ Competition</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="market_outlook" name="market_outlook"
                                        placeholder="Enter Market Outlook/ Competition">
                                    @if ($errors->has('market_outlook'))
                                        <span class="text-danger">{{ $errors->first('market_outlook') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="selling_reason" class="col-sm-2 col-form-label">Reason For Selling</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="selling_reason" name="selling_reason"
                                        placeholder="Enter Reason For Selling">
                                    @if ($errors->has('selling_reason'))
                                        <span class="text-danger">{{ $errors->first('selling_reason') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="train_support" class="col-sm-2 col-form-label">Training/Support</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="train_support" name="train_support"
                                        placeholder="Enter Training/Support">
                                    @if ($errors->has('train_support'))
                                        <span class="text-danger">{{ $errors->first('train_support') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" aria-label="Default select example">
                                        <option selected="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-10 ms-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
