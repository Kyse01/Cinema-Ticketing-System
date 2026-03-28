<!DOCTYPE html>
<html>
<head>
    <title>Account Activation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333;">Welcome to Cinematique!</h2>
        <p>You're almost there! We just need to verify your email address to complete your registration.</p>
        <p>Please click the button below to activate your account:</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $activationLink }}" style="background-color: #FFC90D; color: #000; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold;">Activate Account</a>
        </div>
        <p>If you did not create an account, no further action is required.</p>
        <p>Thanks,<br>The Cinematique Team</p>
    </div>
</body>
</html>
