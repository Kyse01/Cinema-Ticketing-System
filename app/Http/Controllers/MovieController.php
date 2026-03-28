<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieSchedule;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = [
            ['title'=> 'All Movies', 'data_genre' => 'all'],
            ['title'=> 'Action', 'data_genre' => 'action'],
            ['title'=> 'Adventure', 'data_genre' => 'adventure'],
            ['title'=> 'Horror', 'data_genre' => 'horror'],
            ['title'=> 'Thriller', 'data_genre' => 'thriller'],
            ['title'=> 'Fantasy', 'data_genre' => 'fantasy'],
            ['title'=> 'Suspense', 'data_genre' => 'suspense'],
            ['title'=> 'Sci-Fi', 'data_genre' => 'sci-fi'],
        ];

        $movies = Movie::all();

        return view('movies', compact('genres', 'movies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource (schedule page).
     * Passes movie + its real schedule data grouped by branch.
     */
    public function show($id)
    {
        $movie = Movie::findOrFail($id);

        // Load all MovieSchedule entries for this movie with schedule + cinema
        $movieSchedules = MovieSchedule::with(['schedule.cinema'])
            ->where('movie_id', $id)
            ->get();

        // Build branches list (unique)
        $branches = $movieSchedules
            ->pluck('schedule.cinema.branch')
            ->filter()
            ->unique()
            ->values();

        // Build schedules keyed by branch → [ ['date'=>..., 'time'=>..., 'cinema_type'=>..., 'schedule_id'=>... ] ]
        $schedulesByBranch = [];
        foreach ($movieSchedules as $ms) {
            $cinema = $ms->schedule?->cinema;
            $branch = $cinema?->branch;
            if (!$branch) continue;

            $start = $ms->schedule->start;
            $schedulesByBranch[$branch][] = [
                'schedule_id'        => $ms->id,
                'date'               => \Carbon\Carbon::parse($start)->format('D M d'),
                'time'               => \Carbon\Carbon::parse($start)->format('g:i A'),
                'cinema_type'        => $cinema->type,
                'cinema_hall'        => $cinema->hall,
                'cinema_price'       => $cinema->price,
                'cinema_description' => $cinema->description,
            ];
        }

        return view('schedule', compact('movie', 'branches', 'schedulesByBranch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
