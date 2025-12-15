<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\RoomType;

class AdminReviewController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $query = Review::with(['user', 'roomType', 'room']);

        if (request()->filled('room_id')) {
            $query->where('room_id', request('room_id'));
        }

        if (request()->filled('room_type')) {
            $query->where('room_type_id', request('room_type'));
        }

        if (request()->filled('rating')) {
            $query->where('rating', request('rating'));
        }

        if (request()->filled('visible')) {
            $vis = request('visible');
            if (in_array($vis, ['0', '1'], true)) {
                $query->where('is_visible', $vis);
            }
        }

        $reviews = $query->orderByDesc('created_at')->paginate(12)->withQueryString();
        $roomTypes = RoomType::orderBy('name')->get();

        return view('admin.manage_reviews', compact('reviews', 'roomTypes'));
    }

    public function toggleVisibility(Review $review)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $review->is_visible = !$review->is_visible;
        $review->save();

        return back()->with('success', 'Review visibility updated.');
    }

    public function destroy(Review $review)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}

