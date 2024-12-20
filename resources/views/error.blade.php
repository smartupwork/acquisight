<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error {{ $code ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="error-container">
        <h1>Error {{ $code ?? 'Unknown' }}</h1>
        <p>{{ $message ?? 'An unexpected error occurred.' }}</p>
        <a href="{{ url('/') }}">Go Back to Home</a>
    </div>
</body>

</html>
