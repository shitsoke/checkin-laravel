<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Official Receipt — CI{{ str_pad($booking->id,6,'0',STR_PAD_LEFT) }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #f8f9fa; font-family: "Courier New", Courier, monospace; color: #000; }
    .receipt-container { max-width: 720px; margin: 20px auto; background: white; border: 2px solid #000; padding: 20px; }
    .receipt-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 15px; margin-bottom: 15px; }
    .hotel-name { font-size: 24px; font-weight: 900; text-transform: uppercase; }
    .receipt-title { font-size: 16px; font-weight: 900; text-transform: uppercase; text-align: center; margin: 10px 0; }
    .line-items td, .line-items th { padding: 6px; }
    .barcode { font-family: "Libre Barcode 128", monospace; font-size: 36px; }
    .actions { text-align: center; margin-top: 20px; }
    .btn-receipt { background: #000; color: white; border: 2px solid #000; padding: 8px 20px; font-weight: 900; }
  </style>
</head>
<body>
@php
use Illuminate\Support\Carbon;
@endphp

<div class="receipt-container">
    <div class="receipt-header">
        <h1 class="hotel-name">CHECKIN HOTEL</h1>
        <div class="hotel-address">Sudlon, Maguikay, Mandaue City 6014 Cebu City Central Visayas</div>
        <div class="hotel-contact">Tel: (02) 8123-4567 | Email: info@checkinhotel.com</div>
    </div>

    <div class="receipt-title">OFFICIAL RECEIPT</div>

    <div class="receipt-info mb-3">
        <div><strong>Receipt No:</strong> CI{{ str_pad($booking->id,6,'0',STR_PAD_LEFT) }}</div>
        <div><strong>Date Issued:</strong> {{ Carbon::parse($booking->created_at)->format('M j, Y g:i A') }}</div>
        <div><strong>Booking Ref:</strong> #{{ $booking->id }}</div>
        <div><strong>Status:</strong> {{ strtoupper($booking->status) }}</div>
    </div>

    <div class="receipt-info mb-3">
        <div><strong>Guest Name:</strong> {{ trim($booking->user->first_name . ' ' . ($booking->user->middle_name ?? '') . ' ' . $booking->user->last_name) }}</div>
        <div><strong>Room:</strong> {{ $booking->room->room_number }} - {{ $booking->room->type->name ?? '' }}</div>
        <div><strong>Duration:</strong> {{ $booking->hours }} hour{{ $booking->hours > 1 ? 's' : '' }}</div>
        @if(!empty($booking->check_in) && !empty($booking->check_out))
            <div><strong>Check-in:</strong> {{ Carbon::parse($booking->check_in)->format('M j, Y g:i A') }}</div>
            <div><strong>Check-out:</strong> {{ Carbon::parse($booking->check_out)->format('M j, Y g:i A') }}</div>
        @endif
    </div>

    <table class="line-items w-100 mb-3">
        <thead>
            <tr>
                <th class="item-desc">Description</th>
                <th class="text-end">Rate</th>
                <th class="text-end">Hr</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Room Accommodation<br><small>{{ $booking->room->type->name ?? '' }} - {{ $booking->hours }} hour{{ $booking->hours > 1 ? 's' : '' }}</small></td>
                <td class="text-end">{{ number_format($booking->room->type->hourly_rate ?? $booking->total_amount, 2) }}</td>
                <td class="text-end">{{ $booking->hours }}</td>
                <td class="text-end">{{ number_format(($booking->room->type->hourly_rate ?? $booking->total_amount) * $booking->hours, 2) }}</td>
            </tr>
            @php
                $lineTotal = ($booking->room->type->hourly_rate ?? $booking->total_amount) * $booking->hours;
                $extras = json_decode($booking->extra_charges ?? '[]', true) ?: [];
            @endphp
            @foreach($extras as $label => $amt)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-end">{{ number_format($amt,2) }}</td>
                    <td class="text-end">1</td>
                    <td class="text-end">{{ number_format($amt,2) }}</td>
                </tr>
                @php $lineTotal += floatval($amt); @endphp
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="d-flex justify-content-between"><strong>Subtotal:</strong><span>{{ number_format($lineTotal,2) }}</span></div>
        @php
            $feesTotal = (float)($booking->tax ?? 0) + (float)($booking->service_fee ?? 0);
        @endphp
        @if($booking->tax)
            <div class="d-flex justify-content-between"><span>VAT (12%):</span><span>{{ number_format($booking->tax,2) }}</span></div>
        @endif
        @if($booking->service_fee)
            <div class="d-flex justify-content-between"><span>Service Fee:</span><span>{{ number_format($booking->service_fee,2) }}</span></div>
        @endif
        <div class="d-flex justify-content-between grand-total" style="font-weight:900; border-top:2px solid #000; padding-top:8px; margin-top:8px;"><span>TOTAL AMOUNT:</span><span>{{ number_format($booking->total_amount,2) }}</span></div>
    </div>

    <div class="payment-method mt-3">
        <div><strong>Payment Method:</strong> {{ strtoupper($booking->payment_method) }}</div>
        <div><strong>Amount Paid:</strong> {{ number_format($booking->total_amount,2) }}</div>
    </div>

    <div class="stamp-area text-center my-3">
        <div class="stamp" style="display:inline-block;padding:6px 12px;border:2px solid #dc3545;color:#dc3545;font-weight:900;">PAID</div>
        <div style="margin-top:10px;font-size:11px;">{{ now()->format('M j, Y') }}</div>
    </div>

    <div class="barcode-area text-center">
        <div class="barcode">*CI{{ str_pad($booking->id,6,'0',STR_PAD_LEFT) }}*</div>
        <div style="font-size:10px;margin-top:5px;">CI{{ str_pad($booking->id,6,'0',STR_PAD_LEFT) }}</div>
    </div>

    <div class="thank-you text-center mt-3"><strong>THANK YOU FOR CHOOSING CHECKIN HOTEL!</strong><br><span>This receipt is your official proof of payment.</span></div>

    <div class="footer-note text-center mt-3">Receipt generated electronically • Valid without signature<br>{{ now()->format('F j, Y \a\t g:i A') }}</div>

    <div class="actions">
        <button onclick="window.print()" class="btn-receipt me-2"><i class="fas fa-print me-1"></i>Print Receipt</button>
        <a href="{{ route('receipt.show', $booking) }}?download=pdf" class="btn-receipt-outline btn btn-outline-dark"><i class="fas fa-download me-1"></i>Save PDF</a>
    </div>
</div>
</body>
</html>
