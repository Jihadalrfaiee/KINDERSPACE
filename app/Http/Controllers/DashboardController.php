<?php

namespace App\Http\Controllers;

use App\Models\{Student, User, Kindergarten};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات عامة
        $stats = [
            'total_students' => Student::where('status', 'active')->count(),
            'total_teachers' => User::where('role', 'teacher')->where('is_active', true)->count(),
            'total_kindergartens' => Kindergarten::where('is_active', true)->count(),
            'total_parents' => User::where('role', 'parent')->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}