<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomImage;

class AdminRoomController extends Controller
{
    /* =========================
       EXISTING METHODS (UNCHANGED)
    ========================== */

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

        $types = RoomType::all();

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

    /* =========================
       ROOM DETAILS METHODS
    ========================== */

    public function show(Room $room)
    {
        $room->load([
            'type',
            'images',
            'reviews.user'
        ]);

        return view('admin.room_details', compact('room'));
    }

    // 💾 Update Description + Visibility
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'description' => 'nullable|string',
            'is_visible'  => 'nullable|boolean',
        ]);

        $room->update([
            'description' => $request->description,
            'is_visible'  => $request->has('is_visible'),
        ]);

        return redirect()->back()->with('success', 'Saved successfully!');
    }

    /* =========================
       ROOM IMAGES METHODS
    ========================== */

    // 🖼️ Show Room Images Upload Page
    public function showImages(Room $room)
    {
        $room->load('images'); // load existing images
        return view('admin.room_images', compact('room'));
    }

    // 💾 Store Uploaded Image
    public function storeImage(Request $request, Room $room)
{
    $request->validate([
        'images.*' => 'required|image|max:3072', // 3MB max
    ]);

    if($request->hasFile('images')) {
        foreach($request->file('images') as $file) {
            $path = $file->store('room_images', 'public');

            RoomImage::create([
                'room_id' => $room->id,
                'filepath' => $path,
                'is_primary' => $room->images()->count() === 0, // first image is primary
            ]);
        }
    }

    return redirect()->route('admin.rooms.images.upload', $room->id)
                     ->with('success', 'Images uploaded successfully!');
    }

    // ⭐ Make Image Primary
    public function makePrimary(RoomImage $image)
    {
        RoomImage::where('room_id', $image->room_id)
            ->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);

        return redirect()->back();
    }

    // 🗑️ Delete Image
    public function deleteImage(RoomImage $image)
    {
        if ($image->filepath && Storage::disk('public')->exists($image->filepath)) {
            Storage::disk('public')->delete($image->filepath);
        }

        $roomId = $image->room_id;
        $wasPrimary = $image->is_primary;

        $image->delete();

        if ($wasPrimary) {
            RoomImage::where('room_id', $roomId)
                ->orderBy('id')
                ->first()?->update(['is_primary' => true]);
        }

        return redirect()->back();
    }
}
