@extends('layouts.app')

@section('content')
<style>
    :root{
        --primary-color: #dc3545;
        --primary-hover: #c82333;
        --primary-light: rgba(220,53,69,0.08);
    }
    .page-header{ background:var(--primary-color); color:#fff; padding:18px; border-radius:8px; margin-bottom:18px; }
    .btn-back { background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 0.85rem; border-radius: 6px; }
    .card{ border:none; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.06); margin-bottom:1rem; }
    .gallery-row { display:flex; gap:12px; flex-wrap:wrap; }
    .gallery-row .col-md-4 { flex:1 1 calc(33.333% - 12px); min-width:140px; }
    .break-word{ overflow-wrap:anywhere; word-break:break-word; white-space:pre-wrap; }
</style>

<div class="container mt-4 mb-5">

    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="m-0">Room Details</h3>
        <a href="{{ route('rooms.browse') }}" class="btn-back fw-bold">← Back</a>
    </div>

    @if(session('booking_error'))
        <div class="alert alert-danger">{{ session('booking_error') }}</div>
    @endif

    <h4 class="fw-bold text-danger mb-3">Room {{ $room->room_number }} — {{ $room->type->name }}</h4>
    <div class="alert alert-warning">Note: Rates may vary and are subject to change. Final price confirmed at booking.</div>

    <div class="row">
        <div class="col-md-8">
            <div class="card p-3">
                @if($images->count())
                    <div class="row gallery-row">
                        @foreach($images as $im)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $im->filepath) }}" class="img-fluid" style="height:160px;object-fit:cover;">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="height:200px;background:#f8f8f8;display:flex;align-items:center;justify-content:center;color:#777;border-radius:8px;">No images available</div>
                @endif

                <p class="mt-3">{!! nl2br(e($room->description ?? ($room->type->description ?? 'No description available.'))) !!}</p>
                <p><strong>Rate:</strong> ₱{{ number_format($room->type->hourly_rate, 2) }}/hr</p>
            </div>

            <div class="card">
                <div class="card-header">Reviews for this room</div>
                <div class="card-body">
                    <form method="get" class="mb-3">
                        <input type="hidden" name="id" value="{{ $room->id }}">
                        <label class="form-label">Filter by rating:</label>
                        <select name="rating" class="form-select w-auto d-inline-block ms-2" onchange="this.form.submit();">
                            <option value="">All</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ ($ratingFilter == $i) ? 'selected' : '' }}>{{ $i }}/5</option>
                            @endfor
                        </select>
                    </form>

                    @if($reviews->count())
                        @foreach($reviews as $rv)
                            <div class="border rounded p-3 mb-2">
                                <strong>{{ $rv->display_name ?? ($rv->first_name . ' ' . $rv->last_name) }}</strong>
                                <span class="text-muted"> — {{ $rv->created_at }}</span>
                                <div>Rating: <span class="text-danger fw-bold">{{ intval($rv->rating) }}/5</span></div>
                                <div class="break-word">{!! nl2br(e($rv->comment)) !!}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-muted">No reviews yet for this room.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Hotel Overview</div>
                <div class="card-body">
                    <p><strong>Average rating:</strong> {{ $overall->avg_rating ? number_format($overall->avg_rating, 2) : 'N/A' }}</p>
                    <p><strong>Total reviews:</strong> {{ $overall->count_reviews }}</p>
                    <a href="{{ route('reviews.index') }}" class="btn btn-outline-danger btn-sm mt-2">See all overall reviews</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Book / Estimate</div>
                <div class="card-body">
                    @if($room->status === 'available')
                        <form method="post" action="{{ route('booking.store') }}" onsubmit="this.querySelector('button[type=submit]').disabled=true;">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                            <input type="hidden" id="rate_detail" value="{{ $room->type->hourly_rate }}">

                            <label class="form-label">Start (date & time)</label>
                            <input name="start_time" id="start_detail" type="datetime-local" class="form-control mb-2" required min="{{ now()->format('Y-m-d\\TH:i') }}" onchange="normalizeDatetimeToHour('start_detail'); document.getElementById('end_detail').min=this.value; calcEstimateFromTimes('rate_detail','start_detail','end_detail','total_detail')">

                            <label class="form-label">End (date & time)</label>
                            <input name="end_time" id="end_detail" type="datetime-local" class="form-control mb-2" required min="{{ now()->format('Y-m-d\\TH:i') }}" onchange="normalizeDatetimeToHour('end_detail'); calcEstimateFromTimes('rate_detail','start_detail','end_detail','total_detail')">

                            <label class="form-label">Estimate (₱)</label>
                            <input id="total_detail" name="total_est" readonly class="form-control mb-2">

                            <label class="form-label">Payment</label>
                            <select name="payment" class="form-select mb-3" required>
                                <option value="cash">Cash</option>
                                <option value="online">Online</option>
                            </select>

                            <button class="btn btn-success w-100">Book Now</button>
                        </form>
                    @else
                        <div class="alert alert-warning">This room is not available for booking.</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">Leave a Review</div>
                <div class="card-body">
                    @if(request('err') === 'cannot_submit_review')
                        <div class="alert alert-danger">You can only review this room after completing a booking.</div>
                    @endif
                    <form method="post" action="{{ route('reviews.store') }}">
                        @csrf
                        <input type="hidden" name="room_type_id" value="{{ $room->room_type_id }}">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        @php
                            $ret = route('rooms.show', $room->id);
                            if(request('from') === 'bookings') $ret .= '?from=bookings';
                        @endphp
                        <input type="hidden" name="return_to" value="{{ $ret }}">

                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select mb-2">
                            <option value="5">5</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>

                        <label class="form-label">Comment</label>
                        <textarea name="comment" class="form-control mb-3"></textarea>

                        <button class="btn btn-primary w-100">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function parseLocalDatetimeLocal(v) {
    if (!v) return NaN;
    const parts = v.split('T');
    if (parts.length !== 2) return NaN;
    const date = parts[0].split('-').map(x => parseInt(x, 10));
    const time = parts[1].split(':').map(x => parseInt(x, 10));
    if (date.length < 3 || time.length < 2) return NaN;
    return new Date(date[0], date[1] - 1, date[2], time[0], time[1], 0, 0).getTime();
}

function calcEstimateFromHours(rateId, hoursId, totalId) {
    const rateEl = document.getElementById(rateId);
    const hoursEl = document.getElementById(hoursId);
    const totalEl = document.getElementById(totalId);
    if (!rateEl || !hoursEl || !totalEl) return;
    const rate = parseFloat(rateEl.value);
    const hours = parseInt(hoursEl.value);
    if (!isNaN(rate) && !isNaN(hours)) {
        const rateCents = Math.round(rate * 100);
        let totalCents = rateCents * hours;
        totalEl.value = (totalCents / 100).toFixed(2);
    } else totalEl.value = '';
}

function calcEstimateFromTimes(rateId, startId, endId, totalId) {
    const rate = parseFloat(document.getElementById(rateId).value);
    const start = document.getElementById(startId).value;
    const end = document.getElementById(endId).value;
    if (!start || !end || isNaN(rate)) { document.getElementById(totalId).value = ''; return; }
    const sMs = parseLocalDatetimeLocal(start);
    const eMs = parseLocalDatetimeLocal(end);
    if (isNaN(sMs) || isNaN(eMs) || eMs <= sMs) { document.getElementById(totalId).value = ''; return; }
    const hours = Math.ceil((eMs - sMs) / (1000 * 60 * 60));
    const rateCents = Math.round(rate * 100);
    let totalCents = rateCents * hours;
    document.getElementById(totalId).value = (totalCents / 100).toFixed(2);
}

function normalizeDatetimeToHour(elementId) {
    const element = document.getElementById(elementId);
    if (element && element.value) {
        const datetime = element.value;
        if (datetime.includes('T')) {
            const [date, time] = datetime.split('T');
            const [hours] = time.split(':');
            element.value = `${date}T${hours.padStart(2, '0')}:00`;
        }
    }
}
</script>

@endsection
