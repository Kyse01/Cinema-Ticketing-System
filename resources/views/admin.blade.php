@extends('main')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/admin.css" />

<div class="admin-container mt=8">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <nav>
      <ul>
        <li class="active" data-target="dashboardSection">Dashboard</li>
        <li data-target="addMovieSection">Add Movies</li>
        <li data-target="manageSchedules">Manage Schedules</li>
        <li data-target="bookings">Bookings</li>
        <li data-target="cinemas">Cinemas</li>
      </ul>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="content pl-5">
    <!-- Dashboard Section -->
    <section id="dashboardSection" class="section active">
      <h1 class="text-5xl font-bold mb-4 text-amber-300">Welcome to Admin Dashboard</h1>
      <p class="text-lg text-amber-300">Here you can manage movies, schedules, bookings, and cinemas.</p>
    </section>

    <!-- Add Movie Section -->
    <section id="addMovieSection" class="section hidden">
      <form id="addMovieForm" class="movie-form mt-20">
        <div class="form-group">
          <label>Movie Title</label>
          <input type="text" id="movieTitle" required placeholder="Enter movie title" />
        </div>

        <div class="form-group">
          <label>Genre</label>
          <input type="text" id="movieGenre" placeholder="e.g., Action, Comedy" />
        </div>

        <div class="form-group">
          <label>Duration (minutes)</label>
          <input type="number" id="movieDuration" placeholder="120" />
        </div>

        <div class="form-group">
          <label>Rating</label>
          <select id="movieRating">
            <option value="G">G</option>
            <option value="PG">PG</option>
            <option value="R-13">R-13</option>
            <option value="R-16">R-16</option>
            <option value="R-18">R-18</option>
          </select>
        </div>

        <div class="form-group">
          <label>Poster Image URL</label>
          <input type="text" id="moviePoster" placeholder="https://example.com/poster.jpg" />
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea id="movieDescription" rows="4" placeholder="Enter movie synopsis..."></textarea>
        </div>

        <button type="submit" class="btn-add">Add Movie</button>
      </form>
    </section>
  </main>
</div>

<script>
// Sidebar navigation behavior
const sidebarItems = document.querySelectorAll(".sidebar ul li");
const sections = document.querySelectorAll(".section");

sidebarItems.forEach((item) => {
  item.addEventListener("click", () => {
    // Remove active class from all sidebar items
    sidebarItems.forEach((li) => li.classList.remove("active"));
    item.classList.add("active");

    // Hide all sections
    sections.forEach((sec) => sec.classList.add("hidden"));
    sections.forEach((sec) => sec.classList.remove("active"));

    // Show the selected section
    const targetId = item.getAttribute("data-target");
    const targetSection = document.getElementById(targetId);

    if (targetSection) {
      targetSection.classList.remove("hidden");
      targetSection.classList.add("active");
    }
  });
});

// Add Movie Form Handler
document.getElementById("addMovieForm").addEventListener("submit", (e) => {
  e.preventDefault();

  const title = document.getElementById("movieTitle").value.trim();
  const genre = document.getElementById("movieGenre").value.trim();
  const duration = document.getElementById("movieDuration").value;
  const rating = document.getElementById("movieRating").value;
  const poster = document.getElementById("moviePoster").value.trim();
  const description = document.getElementById("movieDescription").value.trim();

  if (!title || !genre || !date) {
    alert("⚠️ Please fill in all required fields!");
    return;
  }

  console.log({
    title,
    genre,
    duration,
    rating,
    poster,
    description,
  });

  alert(`✅ Movie "${title}" added successfully!`);
  e.target.reset();
});
</script>
@endsection