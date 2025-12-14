<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔥 Redirect admin away from user dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $user = Auth::user();

        // User dashboard logic
        $stats = [
            'total' => Booking::where('user_id', $user->id)->count(),
            'upcoming_count' => Booking::where('user_id', $user->id)
                ->where('start_time', '>=', now())
                ->whereIn('status', ['reserved', 'confirmed'])
                ->count(),
            'reviews' => Review::where('user_id', $user->id)->count(),
        ];

        $upcoming = Booking::with('room')
            ->where('user_id', $user->id)
            ->where('start_time', '>=', now())
            ->whereIn('status', ['reserved', 'confirmed'])
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('user', 'stats', 'upcoming'));
    }
}
