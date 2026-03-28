<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Panel - Cinematique</title>

  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-[Poppins]">

<div class="admin-container">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <span></span> Cinematique Admin Panel
    </div>
    <nav>
      <ul>
        <li class="active" data-target="dashboardSection">
          <span class="nav-icon"></span> Dashboard
        </li>
        <li data-target="addMovieSection">
          <span class="nav-icon"></span> Add Movies
        </li>
        <li data-target="moviesListSection">
          <span class="nav-icon"></span> All Movies
        </li>
        <li data-target="cinemasSection">
          <span class="nav-icon"></span> Cinemas
        </li>
        <li data-target="manageSchedules">
          <span class="nav-icon"></span> Schedules
        </li>
        <li data-target="bookings">
          <span class="nav-icon"></span> Bookings
        </li>
      </ul>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="content">

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="flash-success" id="flashMsg">{{ session('success') }}</div>
    @endif

    <!-- ═══════════════════ DASHBOARD ═══════════════════ -->
    <section id="dashboardSection" class="section active">
      <div class="dashboard-header">
        <h1>Welcome, Admin </h1>
        <p>Here you can manage movies, schedules, bookings, and cinemas.</p>
      </div>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon"></div>
          <div class="stat-info">
            <span class="stat-num">{{ $movies->count() }}</span>
            <span class="stat-label">Movies</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"></div>
          <div class="stat-info">
            <span class="stat-num">{{ $cinemas->count() }}</span>
            <span class="stat-label">Cinemas</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"></div>
          <div class="stat-info">
            <span class="stat-num">{{ $schedules->count() }}</span>
            <span class="stat-label">Schedules</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"></div>
          <div class="stat-info">
            <span class="stat-num">{{ $bookings->count() }}</span>
            <span class="stat-label">Bookings</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════ ADD MOVIE ═══════════════════ -->
    <section id="addMovieSection" class="section hidden">
      <h1 class="section-title">Add New Movie</h1>
      <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data" class="movie-form">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label>Movie Title <span class="req">*</span></label>
            <input type="text" name="title" required placeholder="e.g. Avengers: Endgame" />
          </div>
          <div class="form-group">
            <label>Genre <span class="req">*</span></label>
            <input type="text" name="genre" required placeholder="e.g. Action, Adventure" />
          </div>
          <div class="form-group">
            <label>Duration <span class="req">*</span></label>
            <input type="text" name="duration" required placeholder="e.g. 2h 30m" />
          </div>
          <div class="form-group">
            <label>Rating <span class="req">*</span></label>
            <select name="rating" required>
              <option value="">Select Rating</option>
              <option value="G">G</option>
              <option value="PG">PG</option>
              <option value="R-13">R-13</option>
              <option value="R-16">R-16</option>
              <option value="R-18">R-18</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Synopsis</label>
            <textarea name="synopsis" rows="4" placeholder="Enter movie synopsis..."></textarea>
          </div>
          <div class="form-group full">
            <label>Poster Image</label>
            <input type="file" name="poster" accept="image/*" id="posterInput" />
            <div class="poster-preview-wrap">
              <img id="posterPreview" src="" alt="" style="display:none;" class="poster-preview" />
            </div>
          </div>
        </div>
        <button type="submit" class="btn-primary">Add Movie</button>
      </form>
    </section>

    <!-- ═══════════════════ ALL MOVIES ═══════════════════ -->
    <section id="moviesListSection" class="section hidden">
      <h1 class="section-title">All Movies</h1>
      @if($movies->isEmpty())
        <p class="empty-msg">No movies found. Add one from the "Add Movies" section.</p>
      @else
        <div class="movie-cards">
          @foreach($movies as $movie)
            <div class="movie-card-admin">
              <img src="{{ $movie->poster ? asset('storage/' . $movie->poster) : 'https://via.placeholder.com/120x160?text=No+Poster' }}"
                   alt="{{ $movie->title }}" class="movie-thumb" />
              <div class="movie-card-info">
                <h3>{{ $movie->title }}</h3>
                <p><span class="badge">{{ $movie->rating }}</span> &bull; {{ $movie->duration }}</p>
                <p class="genre-tag">{{ $movie->genre }}</p>
                <p class="synopsis-short">{{ Str::limit($movie->synopsis, 80) }}</p>
              </div>
              <form action="{{ route('admin.movies.delete', $movie->id) }}" method="POST" class="delete-form"
                    onsubmit="return confirm('Delete movie \'{{ $movie->title }}\'?')">
                @csrf
                <button type="submit" class="btn-danger">🗑 Delete</button>
              </form>
            </div>
          @endforeach
        </div>
      @endif
    </section>

    <!-- ═══════════════════ CINEMAS ═══════════════════ -->
    <section id="cinemasSection" class="section hidden">
      <h1 class="section-title">Manage Cinemas</h1>

      <form action="{{ route('admin.cinemas.store') }}" method="POST" class="movie-form mb-6">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label>Branch Name <span class="req">*</span></label>
            <input type="text" name="branch" required placeholder="e.g. SM Megamall" />
          </div>
          <div class="form-group">
            <label>Type <span class="req">*</span></label>
            <input type="text" name="type" required placeholder="e.g. IMAX, 4DX, Standard" />
          </div>
          <div class="form-group">
            <label>Hall <span class="req">*</span></label>
            <input type="text" name="hall" required placeholder="e.g. Hall 1" />
          </div>
          <div class="form-group">
            <label>Seat Capacity <span class="req">*</span></label>
            <input type="number" name="seat_capacity" required placeholder="80" min="1" />
          </div>
          <div class="form-group">
            <label>Price (PHP) <span class="req">*</span></label>
            <input type="number" name="price" step="0.01" required placeholder="250.00" min="0" />
          </div>
          <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" placeholder="e.g. Luxury experience" />
          </div>
        </div>
        <button type="submit" class="btn-primary">Add Cinema</button>
      </form>

      @if($cinemas->isEmpty())
        <p class="empty-msg">No cinemas added yet.</p>
      @else
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Branch</th>
                <th>Type</th>
                <th>Hall</th>
                <th>Seats</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cinemas as $cinema)
                <tr>
                  <td>{{ $cinema->id }}</td>
                  <td>{{ $cinema->branch }}</td>
                  <td>
                    <span class="badge">{{ $cinema->type }}</span>
                    @if($cinema->description)
                      <div style="font-size: 0.7rem; color: #999; margin-top: 2px;">{{ $cinema->description }}</div>
                    @endif
                  </td>
                  <td>{{ $cinema->hall }}</td>
                  <td>{{ $cinema->seat_capacity }}</td>
                  <td style="color: #FFC90D; font-weight: bold;">₱{{ number_format($cinema->price, 2) }}</td>
                  <td>
                    <form action="{{ route('admin.cinemas.delete', $cinema->id) }}" method="POST" class="inline-form"
                          onsubmit="return confirm('Delete this cinema?')">
                      @csrf
                      <button type="submit" class="btn-danger-sm">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

    <!-- ═══════════════════ SCHEDULES ═══════════════════ -->
    <section id="manageSchedules" class="section hidden">
      <h1 class="section-title">Manage Schedules</h1>

      <form action="{{ route('admin.schedules.store') }}" method="POST" class="movie-form mb-6">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label>Movie <span class="req">*</span></label>
            <select name="movie_id" required>
              <option value="">Select Movie</option>
              @foreach($movies as $movie)
                <option value="{{ $movie->id }}">{{ $movie->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Cinema <span class="req">*</span></label>
            <select name="cinema_id" required>
              <option value="">Select Cinema</option>
              @foreach($cinemas as $cinema)
                <option value="{{ $cinema->id }}">{{ $cinema->branch }} — {{ $cinema->type }} ({{ $cinema->hall }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Start Date & Time <span class="req">*</span></label>
            <input type="datetime-local" name="start" required />
          </div>
          <div class="form-group">
            <label>End Date & Time <span class="req">*</span></label>
            <input type="datetime-local" name="end" required />
          </div>
        </div>
        <button type="submit" class="btn-primary">Add Schedule</button>
      </form>

      @if($schedules->isEmpty())
        <p class="empty-msg">No schedules added yet.</p>
      @else
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Movie</th>
                <th>Branch</th>
                <th>Type</th>
                <th>Hall</th>
                <th>Start</th>
                <th>End</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($schedules as $ms)
                <tr>
                  <td>{{ $ms->id }}</td>
                  <td>{{ $ms->movie?->title ?? '—' }}</td>
                  <td>{{ $ms->schedule?->cinema?->branch ?? '—' }}</td>
                  <td><span class="badge">{{ $ms->schedule?->cinema?->type ?? '—' }}</span></td>
                  <td>{{ $ms->schedule?->cinema?->hall ?? '—' }}</td>
                  <td>{{ \Carbon\Carbon::parse($ms->schedule?->start)->format('M d, Y g:i A') }}</td>
                  <td>{{ \Carbon\Carbon::parse($ms->schedule?->end)->format('g:i A') }}</td>
                  <td>
                    <form action="{{ route('admin.schedules.delete', $ms->id) }}" method="POST" class="inline-form"
                          onsubmit="return confirm('Delete this schedule?')">
                      @csrf
                      <button type="submit" class="btn-danger-sm">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

    <!-- ═══════════════════ BOOKINGS ═══════════════════ -->
    <section id="bookings" class="section hidden">
      <h1 class="section-title">Bookings</h1>
      @if($bookings->isEmpty())
        <p class="empty-msg">No bookings yet.</p>
      @else
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Movie</th>
                <th>Seats</th>
                <th>Date/Time</th>
                <th>Status</th>
                <th>Proof</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bookings as $booking)
                <tr>
                  <td>{{ $booking->id }}</td>
                  <td>{{ $booking->user?->details?->full_name ?? $booking->user?->name ?? 'Guest' }}</td>
                  <td><strong>{{ $booking->movie_title }}</strong></td>
                  <td style="color: #60a5fa; font-weight: bold;">{{ $booking->seats ?: 'N/A' }}</td>
                  <td>{{ \Carbon\Carbon::parse($booking->date_time)->format('M d, Y g:i A') }}</td>
                  <td style="text-align: center;">
                    @if($booking->payment)
                      @if($booking->payment->status === 'approved')
                        <span style="display: inline-block; padding: 4px 10px; background: rgba(74, 222, 128, 0.2); color: #4ade80; font-weight: 600; border-radius: 20px; font-size: 12px;">Approved</span>
                      @elseif($booking->payment->status === 'rejected')
                        <span style="display: inline-block; padding: 4px 10px; background: rgba(248, 113, 113, 0.2); color: #f87171; font-weight: 600; border-radius: 20px; font-size: 12px;">Rejected</span>
                      @else
                        <span style="display: inline-block; padding: 4px 10px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; font-weight: 600; border-radius: 20px; font-size: 12px;">Pending</span>
                      @endif
                    @else
                      <span style="display: inline-block; padding: 4px 10px; background: rgba(156, 163, 175, 0.2); color: #9ca3af; font-weight: 600; border-radius: 20px; font-size: 12px;">No Payment</span>
                    @endif
                  </td>
                  <td style="text-align: center;">
                    @if($booking->payment && $booking->payment->proof_image)
                      <a href="{{ asset('storage/' . $booking->payment->proof_image) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                        View Proof
                      </a>
                    @else
                      <span style="color: #9ca3af; font-size: 12px;">N/A</span>
                    @endif
                  </td>
                  <td>
                    <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                      @if($booking->payment && $booking->payment->status === 'pending')
                        <form action="{{ route('admin.payments.approve', $booking->payment->id) }}" method="POST">
                          @csrf
                          <button type="submit" title="Approve Payment" style="padding: 6px 14px; background: #16a34a; color: white; border: none; border-radius: 6px; cursor: pointer; transition: 0.2s; font-weight: 500;" onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">Approve</button>
                        </form>
                        <form action="{{ route('admin.payments.reject', $booking->payment->id) }}" method="POST">
                          @csrf
                          <button type="submit" title="Reject Payment" style="padding: 6px 14px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; transition: 0.2s; font-weight: 500;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Reject</button>
                        </form>
                      @endif
                      <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                        @csrf
                        <button type="submit" title="Delete Booking" style="padding: 6px 14px; background: #4b5563; color: white; border: none; border-radius: 6px; cursor: pointer; transition: 0.2s; font-weight: 500;" onmouseover="this.style.background='#374151'" onmouseout="this.style.background='#4b5563'">Drop</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

  </main>
</div>

<script>
/* ── Sidebar navigation ── */
const sidebarItems = document.querySelectorAll(".sidebar ul li");
const sections     = document.querySelectorAll(".section");

sidebarItems.forEach(item => {
  item.addEventListener("click", () => {
    sidebarItems.forEach(li => li.classList.remove("active"));
    item.classList.add("active");

    sections.forEach(sec => { sec.classList.add("hidden"); sec.classList.remove("active"); });

    const target = document.getElementById(item.dataset.target);
    if (target) { target.classList.remove("hidden"); target.classList.add("active"); }
  });
});

/* ── Poster preview ── */
document.getElementById("posterInput")?.addEventListener("change", function () {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById("posterPreview");
    img.src = e.target.result;
    img.style.display = "block";
  };
  reader.readAsDataURL(file);
});

/* ── Auto-hide flash message ── */
const flash = document.getElementById("flashMsg");
if (flash) setTimeout(() => flash.style.opacity = "0", 3000);

/* ── Jump to correct tab if flash present (after redirect) ── */
@if(session('active_tab'))
  const tabBtn = document.querySelector(`[data-target="{{ session('active_tab') }}"]`);
  if (tabBtn) tabBtn.click();
@endif
</script>

</body>
</html>