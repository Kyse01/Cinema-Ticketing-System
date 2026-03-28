<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Store payment with proof image upload.
     * Status starts as 'pending' until admin approves.
     */
    public function store(Request $request)
    {
        $booking = Booking::where('user_id', auth()->id())->latest('created_at')->first();

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('payment_proofs', 'public');
        }

        $payment = Payment::create([
            'date_time'           => now(),
            'total_amount'        => $request->input('total'),
            'booking_id'          => $booking->id,
            'payment_method_id'   => $request->input('payment_method_id'),
            'payment_method_name' => $request->input('payment_method_name'),
            'proof_image'         => $proofPath,
            'status'              => 'pending',
        ]);

        return response()->json([
            'success'    => true,
            'booking_id' => $booking->id,
            'payment_id' => $payment->id,
        ]);
    }

    /**
     * Upload proof image for an existing payment (called via AJAX after payment form).
     */
    public function uploadProof(Request $request, $paymentId)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $payment = Payment::findOrFail($paymentId);

        // Delete old proof if exists
        if ($payment->proof_image) {
            Storage::disk('public')->delete($payment->proof_image);
        }

        $proofPath = $request->file('proof_image')->store('payment_proofs', 'public');
        $payment->update(['proof_image' => $proofPath, 'status' => 'pending']);

        return response()->json(['success' => true, 'message' => 'Proof uploaded successfully!']);
    }

    /* ── Admin Actions ── */

    public function approve($id)
    {
        Payment::findOrFail($id)->update(['status' => 'approved']);
        return redirect()->route('admin')->with('success', 'Payment approved!');
    }

    public function reject($id)
    {
        Payment::findOrFail($id)->update(['status' => 'rejected']);
        return redirect()->route('admin')->with('success', 'Payment rejected.');
    }
}
