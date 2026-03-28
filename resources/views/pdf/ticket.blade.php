<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cinematique Ticket - {{ $scanCode }}</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      margin: 0;
      padding: 0;
      color: #333;
      background: #f7f7f7;
    }

    .ticket-container {
      width: 100%;
      max-width: 650px;
      margin: 40px auto;
      background: #ffffffff;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      border: 1px solid #ddd;
      overflow: hidden;
      position: relative;
    }

    .ticket-header {
      background: #e7000b;
      padding: 25px 30px;
      color: white;
      text-align: center;
      border-bottom: 5px solid #FFC90D;
    }

    .logo {
      width: 110px;
      margin-bottom: 10px;
    }

    .ticket-title {
      font-size: 24px;
      font-weight: 800;
      letter-spacing: 2px;
      text-transform: uppercase;
      margin: 0;
    }

    .ticket-body {
      padding: 30px;
      display: table;
      width: 100%;
      box-sizing: border-box;
    }

    .ticket-info {
      display: table-cell;
      width: 60%;
      vertical-align: top;
      border-right: 2px dashed #ccc;
      padding-right: 30px;
    }

    .ticket-barcode {
      display: table-cell;
      width: 40%;
      vertical-align: middle;
      text-align: center;
      padding-left: 30px;
    }

    .info-row {
      margin-bottom: 15px;
    }

    .info-label {
      font-size: 12px;
      color: #888;
      text-transform: uppercase;
      font-weight: bold;
      margin-bottom: 4px;
    }

    .info-value {
      font-size: 18px;
      font-weight: bold;
      color: #111;
      margin: 0;
    }

    .movie-title {
      font-size: 22px;
      font-weight: 900;
      color: #e7000b;
      margin-bottom: 15px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }

    .barcode-img {
      max-width: 100%;
      height: auto;
      margin-bottom: 10px;
    }

    .scan-code-text {
      font-family: monospace;
      font-size: 14px;
      font-weight: bold;
      color: #555;
      letter-spacing: 1px;
    }

    .ticket-footer {
      background: #111;
      color: #bbb;
      text-align: center;
      padding: 15px;
      font-size: 12px;
    }

    .ticket-footer p {
      margin: 5px 0;
    }

    .highlight {
      color: #FFC90D;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="ticket-container">
    <div class="ticket-header">
      <img src="{{ public_path('images/logo.png') }}" alt="Cinematique Logo">
    </div>

    <div class="ticket-body">
      <div class="ticket-info">
        <div class="info-row">
          <div class="info-label">Movie Title</div>
          <div class="info-value">{{ $booking->movie_title }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Booking ID & Date</div>
          <div class="info-value">#{{ $booking->id }} &bull; {{ \Carbon\Carbon::parse($booking->date_time)->format('Y-m-d') }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Branch & Hall</div>
          <div class="info-value">{{ $booking->movieSchedule?->schedule?->cinema?->branch ?? 'N/A' }} - {{ $booking->movieSchedule?->schedule?->cinema?->hall ?? 'N/A' }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Cinema Type</div>
          <div class="info-value">{{ $booking->cinema_type ?? 'Standard' }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Seat(s)</div>
          <div class="info-value" style="color: #e7000b;">{{ $booking->seats }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Payment Total</div>
          <div class="info-value">₱{{ number_format($payment->total_amount, 2) }}</div>
        </div>
      </div>

      <div class="ticket-barcode">
        <!-- Using an external barcode generator API -->
        <img class="barcode-img" src="https://barcode.tec-it.com/barcode.ashx?data={{ $scanCode }}&code=Code128&translate-esc=true" alt="Barcode for {{ $scanCode }}">
        <div class="scan-code-text">{{ $scanCode }}</div>
        
        <div style="margin-top: 20px; font-size: 12px; color: #777;">
          Present this at the entrance
        </div>
      </div>
    </div>

    <div class="ticket-footer">
      <p>Cinematique Online Ticketing System</p>
      <p>Valid only with matching ID and <span class="highlight">Approved</span> payment confirmation.</p>
    </div>
  </div>

</body>
</html>