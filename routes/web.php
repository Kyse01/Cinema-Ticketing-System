<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register-user', [RegisterController::class, 'register'])->name('register-user');
Route::get('/activate/{token}', [RegisterController::class, 'activate'])->name('activate.account');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login-user', [LoginController::class, 'authenticate'])->name('login-user');

Route::get('/login/otp', [LoginController::class, 'showOtpForm'])->name('login.otp');
Route::post('/login/otp-verify', [LoginController::class, 'verifyOtp'])->name('login.otp.verify');

Route::get('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');

/* ── Public ── */
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/cinema', [CinemaController::class, 'index'])->name('cinema');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

/* ── Auth-required ── */
Route::middleware(['auth'])->group(function () {
    Route::get('/schedule/{id}', [MovieController::class, 'show'])->name('schedule');

    /* Admin dashboard */
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');

    /* Admin — Movies */
    Route::post('/admin/movies', [AdminController::class, 'storeMovie'])->name('admin.movies.store');
    Route::post('/admin/movies/{id}/delete', [AdminController::class, 'deleteMovie'])->name('admin.movies.delete');

    /* Admin — Cinemas */
    Route::post('/admin/cinemas', [AdminController::class, 'storeCinema'])->name('admin.cinemas.store');
    Route::post('/admin/cinemas/{id}/delete', [AdminController::class, 'deleteCinema'])->name('admin.cinemas.delete');

    /* Admin — Schedules */
    Route::post('/admin/schedules', [AdminController::class, 'storeSchedule'])->name('admin.schedules.store');
    Route::post('/admin/schedules/{id}/delete', [AdminController::class, 'deleteSchedule'])->name('admin.schedules.delete');

    /* Admin — Bookings */
    Route::post('/admin/bookings/{id}/delete', [AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');

    /* Admin — Payment approval */
    Route::post('/admin/payments/{id}/approve', [PaymentController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/admin/payments/{id}/reject', [PaymentController::class, 'reject'])->name('admin.payments.reject');

    /* Booking & payment */
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    Route::post('/booking/{id}', [BookingController::class, 'destroy']);
    Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');
    Route::post('/payment/{id}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payment.upload-proof');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/ticket/download/{bookingId}', [TicketController::class, 'download'])->name('ticket.download');
});

Route::get('/booked-seats/{movie_id}', [BookingController::class, 'getBookedSeats']);
