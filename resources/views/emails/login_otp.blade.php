<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Login OTP</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f7f6;
            padding: 40px 0;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .email-header {
            background-color: #4f46e5;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .email-body {
            padding: 40px 30px;
            text-align: center;
            color: #333333;
        }
        .email-body h2 {
            margin-top: 0;
            font-size: 20px;
            color: #1a1a1a;
        }
        .email-body p {
            font-size: 16px;
            line-height: 1.6;
            color: #555555;
            margin: 15px 0;
        }
        .otp-container {
            margin: 30px 0;
            padding: 15px;
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            display: inline-block;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 6px;
            color: #4f46e5;
            margin: 0;
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .email-footer p {
            font-size: 13px;
            color: #888888;
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                <h1>FlipCRM Workspace</h1>
            </div>
            <div class="email-body">
                <h2>Secure Login Verification</h2>
                <p>Hello,</p>
                <p>You requested a One-Time Password (OTP) to sign in to your account. Please use the verification code below to complete your login securely.</p>
                
                <div class="otp-container">
                    <p class="otp-code">{{ $otp }}</p>
                </div>
                
                <p>This code is valid for a limited time. If you did not request this OTP, please ignore this email or contact support if you have concerns.</p>
            </div>
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} FlipCRM. All rights reserved.</p>
                <p>Please do not reply to this automated message.</p>
            </div>
        </div>
    </div>
</body>
</html>
