<!DOCTYPE html>
<html>
<head>
    <title>Deal Request Approved</title>
</head>
<body>

    {{dd($dealRequest)}}
    <h1>Congratulations!</h1>
    <p>Dear {{ $dealRequest->user->name }},</p>

    <p>Your deal request has been <strong>approved</strong>.</p>

    <h3>Deal Details:</h3>
    <ul>
        <li>Deal ID: {{ $dealRequest->deal->gcs_deal_id }}</li>
        <li>Status: {{ ucfirst($dealRequest->status) }}</li>
        <li>Requested On: {{ $dealRequest->created_at->format('d M Y') }}</li>
    </ul>

    <p>Best regards,</p>
    <p>Acquisight</p>
</body>
</html>
