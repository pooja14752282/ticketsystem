<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Show login form
    public function login()
    {
        return view('auth.login');
    }

    // Handle login form submission
    public function loginStore(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    return back()->with('error', 'Invalid email or password!');
}

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Forgot password form
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Handle forgot password submission
    public function forgotPasswordStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent to your email!');
        }

        return back()->with('error', 'Failed to send reset link. Try again!');
    }

    // Show reset password form
    public function resetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Handle reset password submission
    public function resetPasswordStore(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password reset successfully!');
        }

        return back()->with('error', 'Failed to reset password. Try again!');
    }
}