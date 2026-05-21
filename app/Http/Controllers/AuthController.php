<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:user,admin',   // ✅ ADDED
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,             // ✅ ADDED
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // ✅ Redirect based on role
        return $this->redirectByRole($user);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginStore(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // ✅ Redirect based on role after login too
            return $this->redirectByRole(Auth::user());
        }

        return back()->with('error', 'Invalid email or password!');
    }

    // ✅ Role-based redirect helper
    private function redirectByRole($user)
{
    if ($user->role === 'admin') {
        return redirect()->route('admin.tickets.index');
    }

    if ($user->role === 'support') {
        return redirect()->route('support.tickets'); // ← new support route
    }

    return redirect()->route('dashboard');
}
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

    public function resetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

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
