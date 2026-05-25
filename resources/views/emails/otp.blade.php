<!DOCTYPE html>
<html>
<head>
    <title>Your OTP Code</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Inline styles for email compatibility */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .email-header {
            background-color: #ffde59;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            padding: 30px;
        }
        .email-body h2 {
            margin-top: 0;
            color: #333333;
        }
        .otp-box {
            background-color: #f0f0f5;
            border: 1px dashed #ffde59;
            font-size: 28px;
            letter-spacing: 4px;
            color: #000000;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            margin: 20px 0;
        }
        .email-footer {
            padding: 20px;
            font-size: 13px;
            text-align: center;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Your OTP Code</h1>
        </div>
        <div class="email-body">
            <h2>Hello {{ $info['name'] ?? 'User' }},</h2>
            <p>Thank you for using our service. Please use the OTP below to proceed:</p>

            <div class="otp-box">
                {{ $info['otp'] ?? '******' }}
            </div>

            <p>This OTP is valid for <strong>5 minutes</strong>. Please do not share it with anyone.</p>
        </div>
        <div class="email-footer">
            Regards,<br>
            <strong>Smoothride</strong><br>
            <a href="https://smoothride.in/" style="color: #ffde59; text-decoration: none;">smoothride.in</a>
        </div>
    </div>
</body>
</html>
