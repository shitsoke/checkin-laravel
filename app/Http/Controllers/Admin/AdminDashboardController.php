<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Booking;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Only allow admin users
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Dashboard data
        $available_rooms = Room::where('status', 'available')->count();

        $reserved_rooms = Room::join('bookings', 'bookings.room_id', '=', 'rooms.id')
            ->where('bookings.status', 'reserved')
            ->distinct('rooms.id')
            ->count('rooms.id');

        $ongoing_rooms = Booking::whereIn('status', ['confirmed', 'ongoing'])->count();

        return view('admin.dashboard', compact('available_rooms', 'reserved_rooms', 'ongoing_rooms'));
    }
}
