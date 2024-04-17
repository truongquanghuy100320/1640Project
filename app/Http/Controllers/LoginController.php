<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public  function login()
    {
        return view('login.login');

    }

    public function login1(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate user
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            // Retrieve user
            $user = auth()->user();


            // Check if user is active
            if ($user->status === 1) {
                // Store user_id in session
                $request->session()->put('staff_id', $user->staff_id);

                // Check user's role and redirect accordingly
                if ($user->role->status === 1 || $user->role->status === 2 || $user->role->status === 3) {
                    // Redirect to admin dashboard
                    return redirect()->route('homes.dashboard');
                } else {
                    // Logout user if their role is not allowed
                    auth()->logout();
                    return redirect()->back()->with('warning', 'You are not logged in.');
                }
            } elseif ($user->status === 2) {
                // User account is locked
                return redirect()->back()->with('error', 'Your account is locked. Please contact support.');
            } else {
                // Logout user if their account is inactive
                auth()->logout();
                return redirect()->back()->with('error', 'Your account is inactive.');
            }
        } else {
            // Authentication failed
            return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
        }
    }
    public  function  logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login.login')->with('sussec','You have been logged out');
    }

}
