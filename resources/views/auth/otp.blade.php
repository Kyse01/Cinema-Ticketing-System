<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>OTP Verification - Cinematique</title>
    </head>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<body class="relative"> 
<div class="absolute inset-0 bg-black/45 z-0"></div> 
<div class="relative z-10">

<style>
body {
  background-image: url('../images/homepage_bg.jpg');
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  min-height: 100vh;
  margin: 0;
  font-family: "Poppins", sans-serif;
  color: #fff;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.login-box {
  background: #8E0204;
  border-radius: 12px;
  padding: 40px 50px;
  width: 400px;
  box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
  text-align: center;
}

.subtitle {
  font-size: 1rem;
  color: #fff;
  margin-bottom: 25px;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
  text-align: left;
}

.form-group label {
  color: #FFC90D;
  font-weight: 600;
  margin-bottom: 5px;
  display: block;
}

.form-group input {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: none;
  outline: none;
  background: #fff;
  color: #000;
  font-size: 1.25rem;
  text-align: center;
  letter-spacing: 5px;
  font-weight: 700;
  transition: 0.3s;
}

.form-group input:focus {
  box-shadow: 0 0 0 3px #ffc90d80;
}

.login-btn {
  background: #FFC90D;
  color: #000;
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
}

.login-btn:hover {
  background: #e5b800;
}

.note {
  font-size: 0.85rem;
  color: #ddd;
  margin-top: 20px;
}
</style>

<div class="login-container">
    <div class="login-box">
      <img class="logo h-12 mb-3 block mx-auto" src="{{ asset('images/logo.png') }}" alt="Cinematique Logo">
      <h2 class="subtitle">Enter OTP Code</h2>

      @if(session('success'))
          <div style="background-color: #10b981; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
              {{ session('success') }}
          </div>
      @endif
      
      @if($errors->any())
          <div style="background-color: #ef4444; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
              @foreach ($errors->all() as $error)
                  <p>{{ $error }}</p>
              @endforeach
          </div>
      @endif

      <form id="otpForm" class="login-form" method="POST" action="{{ route('login.otp.verify') }}">
        @csrf
        <div class="form-group">
          <label for="otp">One-Time Password</label>
          <input type="text" id="otp" name="otp" placeholder="123456" maxlength="6" required autofocus>
        </div>

        <button type="submit" class="login-btn">Verify and Login</button>
      </form>
        <a href="{{ route('login') }}" style="color: #FFC90D; margin-top: 15px; display: inline-block;">Cancel</a>
      <p class="note">© 2025 Cinematique. All Rights Reserved.</p>
    </div>
  </div>
  
</body>
</html>
