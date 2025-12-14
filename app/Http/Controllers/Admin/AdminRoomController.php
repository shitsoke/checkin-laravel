<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;

class AdminRoomController extends Controller
{
    public function index(Request $request)
{
    $query = Room::with('type');

    if ($request->filled('q')) {
        $query->where('room_number', 'like', '%'.$request->q.'%')
              ->orWhere('description', 'like', '%'.$request->q.'%');
    }

    if ($request->filled('room_type')) {
        $query->where('room_type_id', $request->room_type);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('visible')) {
        $query->where('is_visible', $request->visible);
    }

    $rooms = $query->orderByRaw("status='available' DESC")
                   ->orderBy('room_number')
                   ->get();

    $types = RoomType::all(); // fetch dynamic room types

    return view('admin.manage_rooms', compact('rooms', 'types'));
}

public function store(Request $request)
{
    $request->validate([
        'room_number' => 'required|unique:rooms,room_number',
        'room_type_id' => 'required|exists:room_types,id',
        'description' => 'nullable|string',
        'is_visible' => 'nullable|boolean',
    ]);

    Room::create([
        'room_number' => $request->room_number,
        'room_type_id' => $request->room_type_id,
        'description' => $request->description,
        'is_visible' => $request->has('is_visible'),
    ]);

    return redirect()->back()->with('success', 'Room added successfully.');
}

    public function toggleVisibility(Room $room)
    {
        $room->is_visible = !$room->is_visible;
        $room->save();

        return redirect()->back()->with('success', 'Room visibility updated.');
    }
}
