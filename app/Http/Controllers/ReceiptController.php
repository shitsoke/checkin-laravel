<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        if ($user->role !== 'admin' && $booking->user_id !== $user->id) {
            abort(403);
        }

        $booking->load(['room.type', 'user', 'room']);

        // DOWNLOAD PDF
        if ($request->query('download') === 'pdf') {

            $pdf = Pdf::loadView('receipts.show', compact('booking'))
                ->setPaper('A4', 'portrait');

            return $pdf->download(
                'receipt_CI' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.pdf'
            );
        }

        // DOWNLOAD HTML
        if ($request->query('download') === 'html') {

            $html = view('receipts.show', compact('booking'))->render();

            return response($html, 200, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' =>
                    'attachment; filename="receipt_CI' .
                    str_pad($booking->id, 6, '0', STR_PAD_LEFT) .
                    '.html"',
            ]);
        }

        return view('receipts.show', compact('booking'));
    }
}
