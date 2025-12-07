@extends('layouts.app')

@section('content')
<div class="main-content">

    <div class="welcome-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2>
                    Welcome,
                    {{ $user->profile->display_name ?? $user->first_name . ' ' . $user->last_name }}
                </h2>

                <p class="mb-0">Role: <strong>{{ $user->role }}</strong></p>
            </div>

            <div class="user-avatar">
                @if($user->profile && $user->profile->avatar)
                    <img src="{{ $user->profile->avatar }}" alt="Profile Picture">
                @else
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-bolt me-2"></i> Quick Actions
        </div>

        <div class="card-body">
            <div class="quick-actions d-flex flex-wrap">
                <a href="{{ route('rooms.browse') }}" class="btn btn-primary"><i class="fas fa-door-open me-2"></i> Browse Rooms</a>
                <a href="{{ route('bookings.index') }}" class="btn btn-success"><i class="fas fa-calendar-check me-2"></i> My Bookings</a>
                <a href="{{ route('reviews.index') }}" class="btn btn-warning"><i class="fas fa-star me-2"></i> Leave Review</a>
                <a href="{{ route('settings.index') }}" class="btn btn-secondary"><i class="fas fa-cog me-2"></i> Settings</a>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Upcoming Bookings -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-2"></i> Upcoming Bookings
                </div>

                <div class="card-body">
                    @if($upcoming->count())
                        <div class="list-group list-group-flush">

                            @foreach($upcoming as $u)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="booking-item">
                                    <h6 class="mb-1">Room {{ $u->room->room_number }}</h6>
                                    <small class="text-muted">
                                        {{ $u->start_time->format('M j, Y g:i A') }}
                                        —
                                        {{ $u->end_time->format('M j, Y g:i A') }}
                                    </small>
                                </div>

                                <div class="d-flex align-items-center">
                                    <span class="status-badge status-{{ $u->status }} me-2">
                                        {{ ucfirst($u->status) }}
                                    </span>

                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('bookings.show', $u->id) }}">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    @else
                        <p class="text-muted text-center py-3">No upcoming bookings found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-2"></i> Your Stats
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Total Bookings</h6>
                            <small class="text-muted">All time</small>
                        </div>
                        <span class="badge bg-danger rounded-pill p-2">{{ $stats['total'] }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Upcoming</h6>
                            <small class="text-muted">Future bookings</small>
                        </div>
                        <span class="badge bg-success rounded-pill p-2">{{ $stats['upcoming_count'] }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Reviews</h6>
                            <small class="text-muted">Your feedback</small>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill p-2">{{ $stats['reviews'] }}</span>
                    </div>

                </div>
            </div>

            <!-- Quick Links -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-link me-2"></i> Quick Links
                </div>

                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('rooms.browse') }}" class="btn btn-outline-danger text-start">
                            <i class="fas fa-search me-2"></i> Find Available Rooms
                        </a>

                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-danger text-start">
                            <i class="fas fa-history me-2"></i> Booking History
                        </a>

                        <a href="{{ route('reviews.index') }}" class="btn btn-outline-danger text-start">
                            <i class="fas fa-pen me-2"></i> Write a Review
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
