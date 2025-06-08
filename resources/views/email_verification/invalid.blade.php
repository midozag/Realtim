<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Failed - RealtimeTask</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .verification-card {
            max-width: 600px;
            margin: 80px auto;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .verification-icon {
            text-align: center;
            margin-bottom: 30px;
        }
        .verification-icon svg {
            width: 80px;
            height: 80px;
            color: #ef4444;
        }
        h1 {
            color: #333;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .btn-primary {
            background-color: #4a6cf7;
            border-color: #4a6cf7;
            padding: 10px 20px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #3755d3;
            border-color: #3755d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-card">
            <div class="verification-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h1>Verification Failed</h1>
            
            <p class="text-center">
                {{ $message ?? 'We were unable to verify your email address. The verification link may be invalid or expired.' }}
            </p>
            
            <div class="text-center">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>