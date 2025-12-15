@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: rgba(220, 53, 69, 0.85);
        --primary-solid: #dc3545;
        --secondary-color: #c82333;
        --accent-color: #ff7b7b;
        --sidebar-width: 90px;
    }

    body { background-color: #f9f5f5; }

    .main-content { margin-left: var(--sidebar-width); padding: 20px; transition: all .3s ease; }

    .welcome-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white; border-radius: 15px; box-shadow: 0 10px 20px rgba(220,53,69,0.2);
        padding: 25px; margin-bottom: 30px;
    }

    .card { border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
    .card-header { background: white; border-radius: 12px 12px 0 0; padding: 12px 16px; color: var(--primary-solid); font-weight:600 }

    .quick-actions .btn { border-radius:10px; padding:10px 14px; margin:4px; }

    .status-badge { padding:5px 12px; border-radius:20px; font-size:.85rem; font-weight:600 }

    .user-avatar { width:50px; height:50px; border-radius:50%; overflow:hidden; display:flex; align-items:center; justify-content:center; background:var(--accent-color); color:#fff; font-weight:700 }

    @media (max-width: 768px) {
        .main-content { margin-left:0; padding-top:70px }
    }
</style>

<div class="main-content">

    <!-- Updated Welcome Card with Logout -->
    <div class="welcome-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            
            <!-- Welcome Text -->
            <div>
                <h2>
                    Welcome,
                    {{ $user->profile->display_name ?? $user->first_name . ' ' . $user->last_name }}
                </h2>
                <p class="mb-0">Role: <strong>{{ ucfirst($user->role) }}</strong></p>
            </div>

            <!-- User Avatar + Logout -->
            <div class="d-flex align-items-center gap-3">
                <!-- Avatar -->
                <div class="user-avatar">
                    @if($user->profile && $user->profile->avatar)
                        @php
                            $avatarPath = $user->profile->avatar;
                            $avatarUrl = str_starts_with($avatarPath, 'http')
                                ? $avatarPath
                                : asset('storage/' . ltrim($avatarPath, '/'));
                        @endphp
                        <img src="{{ $avatarUrl }}" alt="Profile Picture" style="width:50px; height:50px; object-fit:cover; border-radius:50%;">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:50px; height:50px; font-weight:bold;">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- keep only avatar; sidebar contains logout -->
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
