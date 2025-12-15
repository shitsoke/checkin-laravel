<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Rooms the user has completed (checked_out)
        $roomsDone = Booking::where('user_id', $userId)
            ->where('bookings.status', 'checked_out')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.id','rooms.room_number','room_types.name as room_type')
            ->distinct()
            ->get();

        // Recent hotel reviews (room_id IS NULL)
        $hotelReviews = Review::with('user')
            ->whereNull('room_id')
            ->where('is_visible', 1)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // My reviews
        $isAdmin = $user->role === 'admin';
        $myReviews = Review::where('user_id', $userId)
            ->when(!$isAdmin, fn($q) => $q->where('is_visible', 1))
            ->leftJoin('room_types','reviews.room_type_id','=','room_types.id')
            ->select('reviews.*', 'room_types.name as room_type')
            ->orderByDesc('reviews.created_at')
            ->get();

        return view('reviews.index', compact('roomsDone','hotelReviews','myReviews','isAdmin'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'nullable|string', // 'hotel' or numeric id
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'return_to' => 'nullable|string'
        ]);

        $userId = Auth::id();
        $returnTo = $request->input('return_to');

        // Hotel review
        if (isset($data['room_id']) && $data['room_id'] === 'hotel') {
            $hasAny = Booking::where('user_id', $userId)
                ->where('status', 'checked_out')
                ->exists();
            if (!$hasAny) {
                $msg = 'You can only leave a hotel review after completing a booking.';
                return redirect($returnTo ?? route('reviews.index') . '?err=cannot_submit_review')->with('info', $msg);
            }

            Review::create([
                'user_id' => $userId,
                'room_type_id' => null,
                'room_id' => null,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'is_visible' => 1,
            ]);

            if ($returnTo) return redirect($returnTo . (strpos($returnTo, '?') === false ? '?' : '&') . 'msg=review_submitted');
            return redirect()->route('reviews.index')->with('success', 'Hotel review submitted.');
        }

        // Room-specific review
        $roomId = is_numeric($data['room_id']) ? intval($data['room_id']) : null;
        if (!$roomId) {
            return redirect($returnTo ?? route('reviews.index'))->with('error', 'Invalid room selected.');
        }

        $hasCompleted = Booking::where('user_id', $userId)
            ->where('room_id', $roomId)
            ->where(function($q) {
                $q->where('status', 'checked_out')
                  ->orWhere('end_time', '<', now());
            })->exists();

        if (!$hasCompleted) {
            if ($returnTo) return redirect($returnTo . (strpos($returnTo, '?') === false ? '?' : '&') . 'err=cannot_submit_review');
            return redirect()->route('rooms.show', $roomId)->with('error', 'You can only review rooms you have completed (checked out).');
        }

        $room = \App\Models\Room::find($roomId);
        $room_type_id = $room->room_type_id ?? null;

        $review = Review::create([
            'user_id' => $userId,
            'room_type_id' => $room_type_id,
            'room_id' => $roomId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_visible' => 1,
        ]);

        if ($review) {
            if ($returnTo) return redirect($returnTo . (strpos($returnTo, '?') === false ? '?' : '&') . 'msg=review_submitted');
            return redirect()->route('rooms.show', $roomId)->with('success', 'Review submitted successfully.');
        }

        if ($returnTo) return redirect($returnTo . (strpos($returnTo, '?') === false ? '?' : '&') . 'err=cannot_submit_review');
        return redirect()->route('rooms.show', $roomId)->with('error', 'Failed to submit review.');
    }
}