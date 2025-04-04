@extends('backend.admin.layout')
@section('admin-deals-setting-content')
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
        @foreach ($deals as $deal)
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ $deal->name }}</strong>
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse"
                                data-bs-target="#deal-settings-{{ $deal->id }}">
                                Settings
                            </button>
                        </div>
                        <div id="deal-settings-{{ $deal->id }}" class="collapse">
                            <div class="card-body">
                                <form method="POST" action="{{ route('settings.list.type', $deal->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">Listing Type</label>
                                        <div class="col-sm-6">
                                            <select name="listing_type" class="form-select">
                                                <option value="public"
                                                    {{ $deal->listing_type === 'public' ? 'selected' : '' }}>
                                                    Public</option>
                                                <option value="private"
                                                    {{ $deal->listing_type === 'private' ? 'selected' : '' }}>
                                                    Private</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Settings</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
