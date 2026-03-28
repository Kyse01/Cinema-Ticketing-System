<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Schedule;
use App\Models\MovieSchedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with all data.
     */
    public function index()
    {
        $movies    = Movie::latest()->get();
        $cinemas   = Cinema::all();
        $bookings  = Booking::with(['user.details', 'movie', 'payment'])->latest()->get();
        $schedules = MovieSchedule::with(['movie', 'schedule.cinema'])->latest()->get();

        return view('admin', compact('movies', 'cinemas', 'bookings', 'schedules'));
    }

    /* ──────────────── MOVIES ──────────────── */

    public function storeMovie(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:100',
            'genre'       => 'required|string',
            'duration'    => 'required|string',
            'rating'      => 'required|string',
            'synopsis'    => 'nullable|string',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        Movie::create([
            'title'    => $request->title,
            'genre'    => $request->genre,
            'duration' => $request->duration,
            'rating'   => $request->rating,
            'synopsis' => $request->synopsis,
            'poster'   => $posterPath,
        ]);

        return redirect()->route('admin')->with('success', 'Movie added successfully!');
    }

    public function deleteMovie($id)
    {
        $movie = Movie::findOrFail($id);

        // Delete poster from storage
        if ($movie->poster) {
            Storage::disk('public')->delete($movie->poster);
        }

        $movie->delete();

        return redirect()->route('admin')->with('success', 'Movie deleted successfully!');
    }

    /* ──────────────── CINEMAS ──────────────── */

    public function storeCinema(Request $request)
    {
        $request->validate([
            'branch'        => 'required|string|max:50',
            'type'          => 'required|string|max:30',
            'hall'          => 'required|string|max:20',
            'seat_capacity' => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'description'   => 'nullable|string|max:255',
        ]);

        Cinema::create([
            'branch'        => $request->branch,
            'type'          => $request->type,
            'hall'          => $request->hall,
            'seat_capacity' => $request->seat_capacity,
            'seat_id'       => 1, // default placeholder
            'price'         => $request->price,
            'description'   => $request->description,
        ]);

        return redirect()->route('admin')->with('success', 'Cinema added successfully!');
    }

    public function deleteCinema($id)
    {
        Cinema::findOrFail($id)->delete();
        return redirect()->route('admin')->with('success', 'Cinema deleted!');
    }

    /* ──────────────── SCHEDULES ──────────────── */

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'cinema_id'  => 'required|exists:cinemas,id',
            'start'      => 'required|date',
            'end'        => 'required|date|after:start',
        ]);

        $schedule = Schedule::create([
            'start'     => $request->start,
            'end'       => $request->end,
            'cinema_id' => $request->cinema_id,
        ]);

        MovieSchedule::create([
            'movie_id'    => $request->movie_id,
            'schedule_id' => $schedule->id,
        ]);

        return redirect()->route('admin')->with('success', 'Schedule added successfully!');
    }

    public function deleteSchedule($id)
    {
        $ms = MovieSchedule::findOrFail($id);
        $scheduleId = $ms->schedule_id;
        $ms->delete();
        Schedule::find($scheduleId)?->delete();

        return redirect()->route('admin')->with('success', 'Schedule deleted!');
    }

    /* ──────────────── BOOKINGS ──────────────── */

    public function deleteBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return redirect()->route('admin')->with('success', 'Booking deleted!');
    }
}
