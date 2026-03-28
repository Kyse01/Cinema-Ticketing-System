<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cinematique</title>

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
  </head>

  <body class="flex flex-col min-h-screen font-[Poppins] relative">
    <div class="fixed inset-0 bg-black/45 z-0"></div>
    <div class="relative z-10 flex flex-col flex-grow">
      
      <!-- Header -->
      <header class="fixed top-0 w-full bg-[#e7000b] bg-opacity-80 z-50">
        <div class="container mx-auto px-6 py-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <img class="logo h-12" src="{{ asset('images/logo.png') }}" alt="Cinematique Logo">
              <div class="film-strip w-20 hidden md:block"></div>
            </div>

            <nav class="flex space-x-6">
              <a href="{{ route('homepage') }}" class="font-bold text-yellow-400 hover:text-yellow-200">Home</a>
              <a href="{{ route('movies.index') }}" class="font-bold text-yellow-400 hover:text-yellow-200">Movies</a>
              <a href="{{ route('contact') }}" class="font-bold text-yellow-400 hover:text-yellow-200">Contact Us</a>

              @if(auth()->user() == null)
                <a href="{{ route('register') }}" class="font-bold text-yellow-400 hover:text-yellow-200">Sign Up</a>
                <a href="{{ route('login') }}" class="font-bold text-yellow-400 hover:text-yellow-200">Log In</a>
              @else
                <a href="{{ route('tickets.index') }}" class="font-bold text-yellow-400 hover:text-yellow-200">My Tickets</a>
                <a href="{{ route('logout') }}" class="font-bold text-yellow-400 hover:text-yellow-200">Log out</a>
                <span class="text-yellow-400">Hi, {{ auth()->user()->details->full_name }}</span>
              @endif
            </nav>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="flex-grow">
        @yield('content')
      </main>

      @if (!request()->routeIs('homepage'))
      <footer class="bg-[#8E0204] text-white py-10 mt-20">
        <div class="max-w-7xl mx-auto px-6 md:px-12 block grid-cols-1 md:grid-cols-3 gap-8">
          
          <div class="flex flex-col items-center text-center">
            <img class="h-24 w-auto mb-4" src="{{ asset('images/logo.png') }}" alt="Cinematique Logo">
            <p class="text-gray-200 text-sm leading-relaxed max-w-md">
              Experience the magic of cinema from the latest blockbusters to timeless classics.
              Book seats and make every movie unforgettable.
            </p>
          </div>

          <div class="mt-4 pt-4 text-center text-sm text-gray-300">
            © 2025 Cinematique. All Rights Reserved.
          </div>

        </div>
      </footer>
      @endif

    </div>
  </body>
</html>
