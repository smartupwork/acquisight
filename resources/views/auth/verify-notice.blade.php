<link href="{{url('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<div class="container">
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">Email Verification Required</div>

        <div class="card-body">
            <p>Please check your email inbox. We've sent you a verification link.</p>
            <p>If you didn't receive it, check your spam folder or try registering again.</p>
        </div>
    </div>
</div>
