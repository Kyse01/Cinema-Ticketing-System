@extends('main')
@section('content')

<link href="{{ asset('css/schedule.css') }}" rel="stylesheet">

<div class="container mx-auto px-4 pt-8 pb-1 mt-24">
  <h1 class="text-3xl text-white font-bold mb-6">Movie Schedule</h1>
</div>

<div class="movie_container">

  <div class="flex space-x-6">
    <img class="w-60 h-auto object-cover rounded-lg shadow-xl"
         src="{{ asset('storage/' . $movie->poster) }}"
         alt="{{ $movie->title }}">

    <div class="flex-grow text-white">
      <h1 class="text-4xl font-extrabold mb-3">
        <span id="movieTitleHeader">{{ $movie->title }}</span>
      </h1>

      <input type="hidden" id="movie_id" value="{{ $movie->id }}">
      <input type="hidden" id="movie_title" value="{{ $movie->title }}">

      <div class="flex items-center font-semibold text-lg mb-2">
        <span class="meta font-semibold">
          {{ $movie->duration }} | {{ $movie->genre }} | {{ $movie->rating }}
        </span>
      </div>

      <div class="mb-6 text-gray-200">
        <p class="text-base leading-relaxed">{{ $movie->synopsis }}</p>
      </div>
    </div>
  </div>

  <!-- Branch Dropdown — populated from DB -->
  <div class="section mt-8">
    <h3 class="text-white font-semibold pb-3">Select Cinema Branch</h3>
    <select id="cinemaBranchDropdown" name="cinema_branch" class="w-full p-3 text-black rounded-lg bg-white cursor-pointer">
      <option value="" disabled selected>Select a Branch...</option>
      @if(count($branches) > 0)
        @foreach($branches as $branch)
          <option value="{{ $branch }}">{{ $branch }}</option>
        @endforeach
      @else
        <option value="" disabled>No branches available for this movie yet</option>
      @endif
    </select>
  </div>

  <!-- Date options — populated dynamically via JS based on selected branch -->
  <div class="section">
    <h3 class="text-white font-semibold pb-3">Select Date</h3>
    <div class="options" id="dates"></div>
    <p id="noDatesMsg" class="text-gray-400 text-sm hidden">No dates available for this branch.</p>
  </div>

  <!-- Time options — populated dynamically via JS based on selected date -->
  <div class="section">
    <h3 class="text-white font-semibold pb-3">Select Time</h3>
    <div class="options" id="times"></div>
    <p id="noTimesMsg" class="text-gray-400 text-sm hidden">No times available for selected date.</p>
  </div>

  <!-- Cinema Type -->
  <div class="section">
    <h3 class="text-white font-semibold pb-3">Select Cinema Type</h3>
    <div class="cinema-type" id="cinemaType">
    </div>
  </div>

  <!-- Seat Layout -->
  <div class="section">
    <h3 class="text-white font-semibold">Select Your Seats</h3>
    <div class="screen">SCREEN</div>
    <div class="seat-layout">
      <div class="seat-wrapper" id="seatWrapper"></div>
    </div>
  </div>

  <!-- Payment Summary -->
  <div class="section payment-summary" id="paymentSummary" style="display:none;">
    <div class="pdf-ticket mx-auto">
      <div class="ticket-header">
        <h2>PAYMENT SUMMARY</h2>
      </div>

      <p><strong>Movie:</strong> <span id="summaryMovieTitle">-</span></p>
      <p><strong>Branch:</strong> <span id="summaryBranch">-</span></p>
      <p><strong>Date:</strong> <span id="summaryDate">-</span></p>
      <p><strong>Time:</strong> <span id="summaryTime">-</span></p>
      <p><strong>Seat(s):</strong> <span id="seatList">None</span></p>
      <p><strong>Type:</strong> <span id="summaryType">-</span></p>
      <p><strong>Ticket(s):</strong> <span id="ticketCount">0x ₱0</span></p>
      <p><strong>Total Payment:</strong> ₱<span id="finalTotal">0</span></p>

      <div class="text-right mt-4">
        <button id="proceedBtn" disabled
          class="bg-[#FFC90D] text-white font-semibold px-6 py-2 rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed">
          Proceed to Payment
        </button>
      </div>
    </div>
  </div>

  <input type="hidden" id="booking_id" value="">
  @include('partials.payment')

</div>

<!-- Pass schedule data from PHP to JS -->
<script>
  const schedulesByBranch = @json($schedulesByBranch);
</script>

<script src="{{ asset('js/schedule.js') }}"></script>
@endsection
