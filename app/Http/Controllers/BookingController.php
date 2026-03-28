<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $movieSchedule = \App\Models\MovieSchedule::with('schedule')->find($request->input('movie_schedule_id'));
        $dateTime = $movieSchedule && $movieSchedule->schedule ? $movieSchedule->schedule->start : now();

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'movie_id' => $request->input('movie_id'),
            'movie_schedule_id' => $request->input('movie_schedule_id'),
            'movie_title' => $request->input('movie_title'),
            'cinema_type' => $request->input('cinema_type'),
            'seats' => $request->input('seats'),
            'date_time' => $dateTime,
            // 'total_amount' => $request->input('total')
        ]);
        return response()->json([
            'success' => true,
            'booking_id' => $booking->id,
        ]);
    }

    public function getBookedSeats(Request $request, $movie_id)
    {
        // Fetch booked seat data properly
        $scheduleId = $request->query('schedule_id');
        
        if (!$scheduleId) {
            return response()->json([]);
        }

        $bookings = Booking::where('movie_id', $movie_id)
                           ->where('movie_schedule_id', $scheduleId)
                           ->pluck('seats');

        $bookedSeats = [];
        foreach ($bookings as $seatString) {
            if (!empty($seatString)) {
                $bookedSeats = array_merge($bookedSeats, explode(',', $seatString));
            }
        }

        return response()->json(array_unique($bookedSeats));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if ($booking) {
            $booking->delete();
            return response()->json(['success' => true, 'message' => 'Unpaid booking deleted']);
        }

        return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
    }
}
