<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Cinematique') }}</title>
    </head>
  
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> 

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

.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.register-box {
  background: #8E0204;
  border-radius: 12px;
  padding: 40px 50px;
  width: 400px;
  box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
  text-align: center;
}

.register-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #FFC90D;
  margin-bottom: 5px;
}

.subtitle {
  font-size: 1rem;
  color: #fff;
  margin-bottom: 25px;
}

.register-form {
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
  font-size: 0.95rem;
  transition: 0.3s;
}

.form-group input:focus {
  box-shadow: 0 0 0 3px #ffc90d80;
}

.register-btn {
  background: #FFC90D;
  color: #000;
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
}

.register-btn:hover {
  background: #e5b800;
}

.note {
  font-size: 0.85rem;
  color: #ddd;
  margin-top: 20px;
}
</style>

<div class="register-container">
    <div class="register-box">
      <img class="logo h-12 mb-3 block mx-auto" src="{{ asset('images/logo.png') }}" alt="Cinematique Logo">
      <h2 class="subtitle">Create your account</h2>

      <form id="registerForm" class="register-form" method="POST" action="{{ route('register-user') }}">
        @csrf
        <div class="form-group">
          <label for="full_name">Full name</label>
          <input type="full_name" id="full_name" name="full_name" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
          <label for="phone_number">Phone Number</label>
          <input type="phone_number" id="phone_number" name="phone_number" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="register-btn">Register</button>
      </form>
      <a href="{{ route('login') }}" style="color: #FFC90D; margin-top: 15px; display: inline-block;">Already have an account? Login</a>
      <a href="{{ route('homepage') }}" style="color: #FFC90D; margin-top: 10px; display: inline-block;">Return to Homepage</a>
      <p class="note">© 2025 Cinematique. All Rights Reserved.</p>
    </div>
</div>
  
</body>
</html>
