<?php
namespace App\Http\Controllers;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with([
            'type',
            'primaryImage',
            'images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('id')
        ])->where('is_visible', 1);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sql) use ($q) {
                $sql->where('room_number', 'LIKE', "%$q%")
                    ->orWhere('description', 'LIKE', "%$q%")
                    ->orWhereHas('type', fn($t) => $t->where('name', 'LIKE', "%$q%"));
            });
        }
        if ($request->filled('room_type')) $query->where('room_type_id', $request->room_type);
        if ($request->filled('status')) $query->where('status', $request->status);

        $rooms = $query->orderByRaw("status = 'available' DESC")->orderBy('room_number')->get();
        $types = RoomType::all();

        return view('rooms.browse', compact('rooms', 'types'));
    }

    public function show($id)
    {
        $room = Room::with(['type'])->findOrFail($id);

        // images ordered
        $images = $room->images()->orderByDesc('is_primary')->orderBy('id', 'asc')->get();

        // rating filter from query
        $ratingFilter = intval(request('rating', 0));

        // fetch reviews with user and profile info (using query builder for simplicity)
        $reviewsQuery = DB::table('reviews as rv')
            ->join('users as u', 'rv.user_id', '=', 'u.id')
            ->leftJoin('profiles as p', 'p.user_id', '=', 'u.id')
            ->select('rv.*', 'u.first_name', 'u.last_name', 'p.display_name')
            ->where('rv.room_id', $id)
            ->where('rv.is_visible', 1);

        if ($ratingFilter >= 1 && $ratingFilter <= 5) {
            $reviewsQuery->where('rv.rating', $ratingFilter);
        }

        $reviews = $reviewsQuery->orderBy('rv.created_at', 'desc')->get();

        // overall hotel reviews
        $overall = DB::table('reviews')
            ->where('is_visible', 1)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as count_reviews')
            ->first();

        return view('rooms.show', compact('room', 'images', 'reviews', 'ratingFilter', 'overall'));
    }
}