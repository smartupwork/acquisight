<!DOCTYPE html>
<html>

<head>
    <title>Deal Access Request</title>
</head>

<body>
    <h2>Hello {{ $broker->name }},</h2>
    <p><strong>{{ $user->name }}</strong> has requested access to the deal content: <strong>{{ $deal->name }}</strong>.</p>
    <p>Please log in to your dashboard to approve or reject this request.</p>
    <p>Thanks,</p>
    <p><strong>{{ config('app.name') }}</strong></p>
</body>

</html>
