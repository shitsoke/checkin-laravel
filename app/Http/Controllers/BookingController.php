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
        $query = Booking::with(['room.type'])->where('user_id', Auth::id());
        
        // Filters from bookings.php
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('from_date')) $query->whereDate('start_time', '>=', $request->from_date);
        
        $bookings = $query->orderBy('created_at', 'DESC')->get();
        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        // Validation Logic
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $hours = $start->diffInHours($end);

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
            return back()->with('error', $e->getMessage());
        }
    }
}