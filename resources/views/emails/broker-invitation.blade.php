<!DOCTYPE html>
<html>
<head>
    <title>Deal Invitation Sent</title>
</head>
<body>
    <p>Hi,</p>
    <p>You have invited a Seller to collaborate on the deal:</p>

    <h3>Seller Email: {{ $seller_email }}</h3>
    <h3>Deal Title: {{ $dealTitle }}</h3>
    <p>Deal Description: {{ $dealDescription }}</p>

    <p>Deal link:</p>
    <p>
        <a href="{{ $link }}" style="padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
            Deal
        </a>
    </p>

    <p>Best regards,</p>
    <p>Acquisight Team</p>
</body>
</html>
