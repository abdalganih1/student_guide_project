<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Use the default Auth facade
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     * Apply guest middleware for the 'admin_web' guard, except for the logout method.
     */
    public function __construct()
    {
        $this->middleware('guest:admin_web')->except('logout');
    }

    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login'); // Assumes this view exists
    }

    /**
     * Handle an incoming admin authentication request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_field' => 'required|string', // اسم الحقل الجديد في النموذج
            'password' => 'required|string',
        ]);
        $loginType = filter_var($request->input('login_field'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Define credentials for the 'admins' provider
        $credentials = [
            $loginType => $request->input('login_field'),
            'password' => $request->password, // Password will be checked against 'password_hash'
        ];

        // Attempt to authenticate using the 'admin_web' guard
        if (Auth::guard('admin_web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard')); // Redirect to admin dashboard
        }

        // If authentication fails
        throw ValidationException::withMessages([
            'login_field' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the admin user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin_web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.form'); // Redirect to admin login page
    }
}