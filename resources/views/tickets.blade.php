@extends('main')

@section('content')
<div class="px-6 py-10 mt-20 max-w-7xl mx-auto min-h-screen">

  @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
      <p>{{ session('error') }}</p>
    </div>
  @endif

  @if($bookings->isEmpty())
    <div class="bg-[#e7000b] bg-opacity-80 z-50 rounded-xl shadow-lg p-10 text-center">
      <div class="text-6xl mb-4"></div>
      <h2 class="text-2xl font-bold text-[#FFC90D] mb-2">No tickets found</h2>
      <p class="text-[#FFC90D] mb-6">Looks like you haven't booked any movies yet.</p>
      <a href="{{ route('movies.index') }}" class="bg-[#FFC90D] text-black px-8 py-3 rounded-full font-bold hover:bg-yellow-400 transition">Get Started</a>
    </div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($bookings as $booking)
        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl transition-shadow duration-300">
          <div class="bg-gradient-to-r from-[#111] to-[#333] text-white p-4 flex justify-between items-center">
            <h3 class="font-bold text-lg truncate pr-4">{{ $booking->movie_title }}</h3>
            <span class="bg-[#FFC90D] text-black text-xs font-bold px-2 py-1 rounded">ID: #{{ $booking->id }}</span>
          </div>
          
          <div class="p-5 flex-grow">
            <div class="flex items-center gap-2 mb-3 text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>{{ $booking->movieSchedule?->schedule?->cinema?->branch ?? 'N/A' }} - {{ $booking->movieSchedule?->schedule?->cinema?->hall ?? 'N/A' }}</span>
            </div>

            <div class="flex items-center gap-2 mb-3 text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <span>{{ $booking->cinema_type ?? 'Standard Cinema' }}</span>
            </div>

            <div class="flex items-center gap-2 mb-3 text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span>{{ \Carbon\Carbon::parse($booking->date_time)->format('F j, Y, g:i A') }}</span>
            </div>

            <div class="flex items-start gap-2 mb-4 text-sm text-gray-600">
              <div class="h-5 w-5 shrink-0 flex items-center justify-center text-gray-400 mt-0.5"></div>
              <span class="break-words">Seats: <span class="font-semibold text-gray-800">{{ $booking->seats }}</span></span>
            </div>

            <hr class="border-gray-200 mb-4">
            
            <div class="flex justify-between items-center mb-4 text-sm">
              <span class="text-gray-500">Payment Status:</span>
              @if($booking->payment)
                @if($booking->payment->status === 'approved')
                  <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold text-xs ring-1 ring-green-600/20">Approved</span>
                @elseif($booking->payment->status === 'rejected')
                  <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold text-xs ring-1 ring-red-600/20">Rejected</span>
                @else
                  <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-bold text-xs ring-1 ring-yellow-600/20">Pending</span>
                @endif
              @else
                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-bold text-xs">No Payment</span>
              @endif
            </div>

          </div>

          <div class="bg-gray-50 p-4 border-t border-gray-100">
            @if($booking->payment && $booking->payment->status === 'approved')
              <a href="{{ route('ticket.download', $booking->id) }}" class="w-full block text-center bg-[#e7000b] text-white py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                Download Ticket
              </a>
            @elseif($booking->payment && $booking->payment->status === 'pending')
              <button disabled class="w-full block w-full text-center bg-gray-300 text-gray-600 py-2 rounded-lg font-semibold cursor-not-allowed">
                Awaiting Approval
              </button>
            @elseif($booking->payment && $booking->payment->status === 'rejected')
              <button disabled class="w-full block w-full text-center bg-gray-300 text-red-500 py-2 rounded-lg font-semibold cursor-not-allowed">
                Payment Rejected
              </button>
            @else
              <button disabled class="w-full block w-full text-center bg-gray-200 text-gray-500 py-2 rounded-lg font-semibold cursor-not-allowed">
                Incomplete
              </button>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
