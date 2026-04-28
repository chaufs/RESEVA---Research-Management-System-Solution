<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate(true);

        $user = Auth::user();

        if ($user->email === 'admin@reseva.test') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->student) {
            if ($user->student->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Student account is inactive.']);
            }

            return redirect()->route('student.dashboard');
        }

        if ($user->teacher) {
            if ($user->teacher->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Teacher account is inactive.']);
            }

            return redirect()->route('teacher.dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'Unable to determine your role.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // Auth::logoutOtherDevices() removed - prevents password prompt on logout

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function studentDashboard()
    {
        $student = Auth::user()->student;

        if (! $student) {
            return redirect()->route('login');
        }

        return app(StudentController::class)->dashboard();
    }
}

