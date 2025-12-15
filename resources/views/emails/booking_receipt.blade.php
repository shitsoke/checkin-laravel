<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Receipt</title>
</head>
<body>
    <h2>Booking Receipt</h2>
    <p>Hi {{ $booking->user->first_name ?? $booking->user->email }},</p>
    <p>Thank you for staying with us. Here are your booking details:</p>
    <ul>
        <li>Room: {{ $booking->room->room_number }} ({{ $booking->room->type->name ?? '' }})</li>
        <li>Start: {{ $booking->start_time->format('Y-m-d H:i') }}</li>
        <li>End: {{ $booking->end_time->format('Y-m-d H:i') }}</li>
        <li>Hours: {{ $booking->hours }}</li>
        <li>Total: {{ number_format($booking->total_amount, 2) }}</li>
        <li>Payment method: {{ $booking->payment_method }}</li>
    </ul>
    <p>If you have questions, reply to this email.</p>
    <p>Regards,<br/>The Team</p>
</body>
</html>
