<!DOCTYPE html>
<html>
<head>
    <title>Login OTP code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333;">Your Login OTP Code</h2>
        <p>You recently tried to log in to Cinematique. To complete the process, please enter the following One-Time Password (OTP):</p>
        <div style="text-align: center; margin: 30px 0;">
            <p style="background-color: #EEE; display: inline-block; padding: 15px 30px; font-size: 24px; font-weight: bold; letter-spacing: 5px; border-radius: 8px;">{{ $otp }}</p>
        </div>
        <p>This code will expire in 10 minutes. Please do not share this code with anyone.</p>
        <p>If you did not request this code, you can safely ignore this email.</p>
        <p>Thanks,<br>The Cinematique Team</p>
    </div>
</body>
</html>
