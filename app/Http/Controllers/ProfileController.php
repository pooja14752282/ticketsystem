<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
{
    $user = Auth::user();
    return view('profile', compact('user'));
}
    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'current_password'],
        'password'         => ['required', 'min:8', 'confirmed'],
    ]);

    $request->user()->update([
        'password' => bcrypt($request->password),
    ]);

    return back()->with('success', 'Password updated successfully.');
}
}