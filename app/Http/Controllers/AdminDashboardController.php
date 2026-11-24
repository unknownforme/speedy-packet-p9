<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Only admins should access this
        if (!Auth::user()->hasRole('Admin')) {
            abort(403);
        }

        $stats = [
            'total_packages' => Package::count(),
            'active_packages' => Package::where('current_status', '!=', 'Bezorgd')->count(),
            'total_companies' => \App\Models\Company::count(),
            'total_users' => User::count(), // Simplified
            'total_revenue_cents' => Package::sum('shipping_cost_cents'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
