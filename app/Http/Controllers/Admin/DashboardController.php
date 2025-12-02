<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'units_available' => Unit::where('status', 'available')->count(),
            'units_sold' => Unit::where('status', 'sold')->count(),
            'pending_bookings' => Transaction::where('status', 'process')->count(),
            'docs_review' => Transaction::where('status', 'docs_review')->count(),
        ];

        $recent_tasks = Transaction::with(['user', 'unit'])
            ->whereIn('status', ['process', 'docs_review'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_tasks'));
    }
}