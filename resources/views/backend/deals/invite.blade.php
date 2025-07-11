@extends('backend.admin.layout')
@section('admin-deals-invite-content')
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
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Invite Contacts</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form action="{{ route('deals.sendInvite', $deal->id) }}" method="POST">
                            @csrf
                            <div class="mb-3 row">
                                <label for="role" class="col-sm-2 col-form-label">Select Contact Type</label>
                                <div class="col-sm-10">
                                <select class="form-select" id="role" name="roles_id">
                                    <option value="4">Buyer</option>
                                    <option value="3">Seller</option>
                                </select>
                            </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 ms-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div><!--end card-body-->
                </div>
            </div>
        </div>
    </div>
@endsection
