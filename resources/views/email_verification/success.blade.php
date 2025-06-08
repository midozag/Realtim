<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
            color: #10b981;
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h1>Email Successfully Verified!</h1>
            
            <p>
                Thank you, {{ $user->name }}! Your email address ({{ $user->email }}) has been successfully verified. 
                You can now access all features of RealtimeTask.
            </p>
            
            <div class="text-center">
                <a href="{{ url('/app/login') }}" class="btn btn-primary">
                    Continue to dashboard
                </a>
            </div>
        </div>
    </div>
 
</body>
</html>
<script>
    
</script>