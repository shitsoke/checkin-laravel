<?php
namespace App\Http\Controllers;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('type')->where('is_visible', 1);

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
}