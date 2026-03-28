<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            // Generate OTP
            $otp = rand(100000, 999999);
            
            \Illuminate\Support\Facades\Cache::put('otp_' . $user->id, $otp, now()->addMinutes(10));
            \Illuminate\Support\Facades\Session::put('login_user_id', $user->id);
            
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpLoginMail($otp));

            return redirect()->route('login.otp')->with('success', 'OTP sent to your email.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showOtpForm()
    {
        if (!\Illuminate\Support\Facades\Session::has('login_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);
        
        $userId = \Illuminate\Support\Facades\Session::get('login_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $userId);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            \Illuminate\Support\Facades\Cache::forget('otp_' . $userId);
            \Illuminate\Support\Facades\Session::forget('login_user_id');

            $user = \App\Models\User::find($userId);
            auth()->login($user);

            if ($user->type != 1) {
                return redirect('/');
            } else {
                return redirect('/admin');
            }
        }

        return back()->withErrors(['otp' => 'The OTP you entered is incorrect or expired.']);
    }
}
