<!-- resources/views/emails/notification.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            color: #333;
            padding: 20px;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 6px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .email-body {
            font-size: 16px;
            line-height: 1.6;
            color: #444;
        }

        .email-footer {
            margin-top: 30px;
            font-size: 13px;
            color: #aaa;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            {{ $title }}
        </div>
<div class="email-body">
    {!! nl2br(e($content)) !!}
</div>

        <div class="email-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
