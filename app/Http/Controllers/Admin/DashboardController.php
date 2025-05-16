<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Event;
use App\Models\Project;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     * Ensure the user is authenticated via the 'admin_web' guard.
     */
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Fetch some basic statistics
        $stats = [
            'totalStudents' => Student::count(),
            'totalCourses' => Course::count(),
            'totalInstructors' => Instructor::count(),
            'totalEvents' => Event::whereIn('status', ['scheduled', 'ongoing'])->count(),
            'totalProjects' => Project::count(),
        ];

        // You could also fetch recent activities, pending requests, etc.

        return view('admin.dashboard', compact('stats')); // Assumes this view exists
    }
}