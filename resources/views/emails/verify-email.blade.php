<!DOCTYPE html>
<html>

<head>
    <title>Verify Email</title>
</head>

<body>
    <h2>Hi {{ $user->name }},</h2>
    <p>Please verify your email by clicking the link below:</p>

    <a href="{{ $verificationUrl }}">Verify Email</a>

    <p>Best regards,</p>
    <p>Acquisight Team</p>
</body>

</html>
