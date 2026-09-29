<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show headline counts and the most recently added students.
     */
    public function __invoke(): View
    {
        return view('dashboard', [
            'studentCount' => Student::count(),
            'courseCount' => Course::count(),
            'unassignedCount' => Student::whereNull('course_id')->count(),
            'recentStudents' => Student::with('course')->latest()->take(5)->get(),
        ]);
    }
}
