<!DOCTYPE html>
<html>
<head>
    <title>Deal Invitation</title>
</head>
<body>
    <p>Hi,</p>
    <p>You have been invited to collaborate on the deal:</p>

    <h3>{{ $dealTitle }}</h3>
    <p>{{ $dealDescription }}</p>

    <p>Click the link below to register as a seller and begin uploading files:</p>
    <p>
        <a href="{{ $link }}" style="padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
            Upload Files
        </a>
    </p>

    <p>Best regards,</p>
    <p>Acquisight Team</p>
</body>
</html>
