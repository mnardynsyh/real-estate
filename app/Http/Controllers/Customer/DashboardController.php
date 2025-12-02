<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $activeTransaction = Transaction::with(['unit.location'])
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['sold', 'rejected', 'canceled'])
            ->latest()
            ->first();
            
        $recentActivities = Transaction::with('unit')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('activeTransaction', 'recentActivities'));
    }
}