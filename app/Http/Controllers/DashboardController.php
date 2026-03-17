<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Alert;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = Submission::count();
        $totalAlerts = Alert::count();
        $highRiskCount = Submission::where('crop_condition', 'poor')->count();
        $pestReports = Submission::where('pest_detected', true)->count();

        $recentSubmissions = Submission::latest()->take(5)->get();
        $recentAlerts = Alert::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalReports',
            'totalAlerts',
            'highRiskCount',
            'pestReports',
            'recentSubmissions',
            'recentAlerts'
        ));
    }
}

