<!DOCTYPE html>
<html>
<head>
    <title>Deal Collaboration Request</title>
</head>
<body>
    <h2>Hello,</h2>

    <p>You have been invited to collaborate on the deal:</p>
    <h3>Deal Title: {{ $dealTitle }}</h3>
    <p>Deal Description: {{ $dealDescription }}</p>

    <p>Please follow the link to login:
        <a href="{{ route('login-view') }}" style="padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
            Login
        </a>
    </p>

    <p>If you have any questions, please contact support.</p>

    <p>Best regards,</p>
    <p>Acquisight Team</p>
</body>
</html>
