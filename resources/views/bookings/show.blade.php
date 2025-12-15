@extends('layouts.app')

@section('title','Booking Details')

@section('content')
<div class="main-content">
    <div class="container mt-4 mb-5">

        <div class="page-header d-flex justify-content-between align-items-center bg-danger text-white p-3 rounded">
            <h3 class="m-0"><i class="fas fa-calendar-check me-2"></i>Booking Details</h3>
            <a href="{{ route('bookings.index') }}" class="btn btn-light desktop-back-btn">
                <i class="fas fa-arrow-left me-2"></i>Back to Bookings
            </a>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-receipt me-2"></i>Booking #{{ $booking->id }}
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-label fw-semibold text-muted">Customer</div>
                        <div class="detail-value">{{ trim($booking->user->first_name.' '.$booking->user->last_name) }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label fw-semibold text-muted">Room</div>
                        <div class="detail-value">
                            {{ $room->room_number }} ({{ $room->type->name ?? 'N/A' }})
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label fw-semibold text-muted">Start Time</div>
                        <div class="detail-value">{{ $booking->start_time->format('M j, Y g:i A') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label fw-semibold text-muted">End Time</div>
                        <div class="detail-value">{{ $booking->end_time->format('M j, Y g:i A') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label fw-semibold text-muted">Duration</div>
                        <div class="detail-value">{{ intval($booking->hours) }} hour{{ $booking->hours > 1 ? 's' : '' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label fw-semibold text-muted">Total Amount</div>
                        <div class="detail-value text-danger fw-bold">₱{{ number_format($booking->total_amount,2) }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label fw-semibold text-muted">Payment</div>
                        <div class="detail-value">{{ ucfirst($booking->payment_method) }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label fw-semibold text-muted">Status</div>
                        @php
                            $badge = match($booking->status) {
                                'confirmed' => 'bg-success',
                                'reserved' => 'bg-warning text-dark',
                                'ongoing' => 'bg-primary',
                                'checked_out' => 'bg-info',
                                'canceled' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badge }}">{{ ucfirst(str_replace('_',' ',$booking->status)) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('receipt.show', $booking) }}" class="btn btn-outline-danger">
                <i class="fas fa-eye me-1"></i>View Receipt
            </a>
            <a href="{{ route('receipt.show', $booking) }}?download=pdf" class="btn btn-danger">
                <i class="fas fa-download me-1"></i>Download PDF
            </a>
        </div>

        @if(Auth::user()->role !== 'admin' && Auth::id() === $booking->user_id && $booking->status === 'checked_out')
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-star me-2"></i>Leave a Review
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('reviews.store') }}">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $booking->room_id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rating</label>
                            <select name="rating" class="form-select" required>
                                @for($i=5; $i>=1; $i--)
                                    <option value="{{ $i }}">{{ $i }} / 5</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Comment</label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience with this room..."></textarea>
                        </div>
                        <button class="btn btn-danger w-100">
                            <i class="fas fa-paper-plane me-2"></i>Submit Review
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.detail-label { font-size: 0.9rem; }
.detail-value { font-size: 1.05rem; }
.desktop-back-btn { font-weight: 600; }
</style>
@endsection

