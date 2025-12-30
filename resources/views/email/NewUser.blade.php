<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #4a6cf7;
            color: white;
            padding: 15px;
            border-radius: 5px 5px 0 0;
            margin: -20px -20px 20px;
            text-align: center;
        }
        .user-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .user-details p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head><div class="container">
    <div class="header">
        <h2>New User Registration</h2>
    </div>
    
    <p>A new user has registered on RealtimeTask.</p>
    
    <div class="user-details">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Registered At:</strong> {{ $user->created_at->format('F j, Y, g:i a') }}</p>
    </div>
    
    <p>You can view the user's profile by clicking the button below:</p>
    
    <p style="text-align: center;">
        <a href="http://127.0.0.1:8000/validate/{{ $user->id }}/{{ $user->remember_token }}" style="background-color: #4a6cf7; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Validate User Mail
        </a>
    </p>
    
    <div class="footer">
        <p>This is an automated message from RealtimeTask.</p>
        <p>&copy; {{ date('Y') }} RealtimeTask. All rights reserved.</p>
    </div>
</div>
<body>
    
</body>
</html>