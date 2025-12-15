<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingReceipt;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    public function index()
    {
        // Admin access only
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $query = Booking::with(['user','room']);

        // Filters
        if (request()->filled('q')) {
            $q = request('q');
            $query->whereHas('user', fn($u) => $u->where('email', 'LIKE', "%$q%"))
                  ->orWhereHas('room', fn($r) => $r->where('room_number', 'LIKE', "%$q%"));
        }
        if (request()->filled('status')) $query->where('status', request('status'));
        if (request()->filled('room')) $query->where('room_id', request('room'));
        if (request()->filled('from_date')) $query->whereDate('start_time', '>=', request('from_date'));
        if (request()->filled('to_date')) $query->whereDate('start_time', '<=', request('to_date'));

        $bookings = $query->orderByRaw("(start_time >= NOW()) DESC")->orderBy('start_time')->orderByDesc('created_at')->get();

        return view('admin.manage_bookings', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $request->validate(['action' => 'required|string']);
        $action = $request->action;
        $valid = ['confirmed','ongoing','checked_out','canceled'];
        if (!in_array($action, $valid)) return back()->with('error', 'Invalid action');

        DB::transaction(function() use ($booking, $action) {
            $booking->status = $action;
            $booking->save();

            if ($action === 'canceled') {
                $booking->room->update(['status' => 'available']);
            }

            if ($action === 'checked_out') {
                $booking->room->update(['status' => 'available']);
                // generate PDF if Dompdf available and send receipt email (attach PDF where possible)
                try {
                    $pdfContent = null;
                    if (class_exists('Dompdf\\Dompdf')) {
                        $html = view('receipts.show', compact('booking'))->render();
                        $dompdf = new \Dompdf\Dompdf();
                        $dompdf->loadHtml($html);
                        $dompdf->setPaper('A4', 'portrait');
                        $dompdf->render();
                        $pdfContent = $dompdf->output();
                    }

                    Mail::to($booking->user->email)->send(new BookingReceipt($booking, $pdfContent));
                    $booking->receipt_sent = 1;
                    $booking->save();
                } catch (\Exception $e) {
                    // swallow email errors for now
                }
            }

            if ($action === 'confirmed') {
                $booking->room->update(['status' => 'occupied']);
            }
        });

        return back()->with('success', 'Booking updated successfully.');
    }
}

