<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;
use App\Models\Payment;

class TicketController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['movie', 'payment', 'movieSchedule.schedule.cinema'])
                    ->where('user_id', auth()->id())
                    ->latest('created_at')
                    ->get();
        
        return view('tickets', compact('bookings'));
    }

    public function download($bookingId)
    {
        $booking = Booking::with(['movie', 'movieSchedule.schedule.cinema'])->findOrFail($bookingId);

        $payment = Payment::where('booking_id', $bookingId)
                          ->latest('created_at')
                          ->first();

        // Only allow download if payment is approved
        if (!$payment || $payment->status !== 'approved') {
            return back()->with('error', 'Your payment is pending admin approval. You can download your ticket once it is approved.');
        }

        // Generate a unique scannable code for receptionist verification
        $scanCode = strtoupper('TKT-' . $bookingId . '-' . substr(md5($bookingId . $payment->id), 0, 8));

        $pdf = Pdf::loadView('pdf.ticket', compact('booking', 'payment', 'scanCode'));

        return $pdf->download('ticket-' . $booking->id . '.pdf');
    }
}
