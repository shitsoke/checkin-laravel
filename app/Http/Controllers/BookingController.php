<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Mock payment confirmation from payment QR (GET ?paid=1&booking_id=123)
        if ($request->has('paid') && $request->filled('booking_id')) {
            $bid = intval($request->booking_id);
            $booking = Booking::where('id', $bid)->where('user_id', $userId)->first();
            if ($booking) {
                $booking->update(['status' => 'confirmed']);
            }
            return redirect()->route('bookings.index');
        }

        $query = Booking::with(['room.type'])->where('user_id', $userId);

        // Filters
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->whereHas('room', fn($r) => $r->where('room_number', 'LIKE', "%$q%"))
                  ->orWhereHas('room.type', fn($t) => $t->where('name', 'LIKE', "%$q%"))
                  ->orWhere('payment_method', 'LIKE', "%$q%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('room')) $query->whereHas('room', fn($r) => $r->where('room_number', 'LIKE', "%{$request->room}%"));
        if ($request->filled('from_date')) $query->whereDate('start_time', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->whereDate('start_time', '<=', $request->to_date);

        $bookings = $query->orderByDesc('created_at')->paginate(12)->withQueryString();
        return view('bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['room.type', 'user'])->findOrFail($id);

        // allow owner or admin only
        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        $room = $booking->room;

        return view('bookings.show', compact('booking', 'room'));
    }

    public function store(Request $request)
    {
        // Validation Logic
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        // Server-side checks: end must be after start, start must not be in the past
        if ($end->lessThanOrEqualTo($start)) {
            return back()->with('booking_error', 'End time must be after start time.');
        }

        if ($start->lessThan(now())) {
            return back()->with('booking_error', 'Start time must be in the future.');
        }

        $hours = $start->diffInHours($end);
        if ($hours <= 0) {
            return back()->with('booking_error', 'Invalid booking duration.');
        }

        try {
            DB::transaction(function () use ($request, $start, $end, $hours) {
                $room = Room::lockForUpdate()->find($request->room_id);
                
                // Concurrency Check from book_room.php
                $overlap = Booking::where('room_id', $room->id)
                    ->whereIn('status', ['reserved', 'confirmed', 'ongoing'])
                    ->where(fn($q) => $q->where('end_time', '>', $start)->where('start_time', '<', $end))
                    ->exists();

                if ($overlap || $room->status !== 'available') throw new \Exception("Room unavailable.");

                Booking::create([
                    'user_id' => Auth::id(),
                    'room_id' => $room->id,
                    'start_time' => $start,
                    'end_time' => $end,
                    'hours' => $hours,
                    'total_amount' => $room->type->hourly_rate * $hours,
                    'payment_method' => $request->payment,
                    'status' => 'reserved'
                ]);

                $room->update(['status' => 'reserved']);
            });

            return redirect()->route('bookings.index')->with('success', 'Room reserved!');
        } catch (\Exception $e) {
            return back()->with('booking_error', $e->getMessage());
        }
    }
}