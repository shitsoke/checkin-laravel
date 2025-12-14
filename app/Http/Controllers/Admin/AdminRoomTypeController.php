<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomType;

class AdminRoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::orderBy('id')->get();

        return view('admin.manage_room_types', compact('roomTypes'));
    }

    public function edit(RoomType $roomType)
    {
        return view('admin.edit_room_type', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        $roomType->update([
            'name' => $request->name,
            'hourly_rate' => $request->hourly_rate,
        ]);

        return redirect()->route('admin.roomtypes.index')->with('success', 'Room type updated successfully.');
    }
}
