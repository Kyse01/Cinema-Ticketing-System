<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        
        $token = \Illuminate\Support\Str::random(64);
        
        \Illuminate\Support\Facades\Cache::put('activation_' . $token, $validatedData, now()->addMinutes(60));
        
        $activationLink = route('activate.account', ['token' => $token]);
        
        \Illuminate\Support\Facades\Mail::to($validatedData['email'])->send(new \App\Mail\AccountActivationMail($activationLink));

        return redirect()->route('login')->with('success', 'Registration submitted! Please check your email to activate your account.');
    }

    public function activate($token)
    {
        $userData = \Illuminate\Support\Facades\Cache::get('activation_' . $token);

        if (!$userData) {
            return redirect()->route('login')->with('error', 'The activation link is invalid or has expired.');
        }

        $user = new User();
        $user->email = $userData['email'];
        $user->password = bcrypt($userData['password']);
        $user->save();

        if ($user) {
            $user_details = new UserDetail();
            $user_details->user_id = $user->id;
            $user_details->full_name = $userData['full_name'];
            $user_details->phone_number = $userData['phone_number'];
            $user_details->save();
        }

        \Illuminate\Support\Facades\Cache::forget('activation_' . $token);

        return redirect()->route('login')->with('success', 'Account activated successfully! You can now log in.');
    }
}
