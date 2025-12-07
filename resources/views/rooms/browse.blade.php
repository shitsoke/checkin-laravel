@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="page-title"><i class="fas fa-bed me-3"></i>Browse Our Rooms</h1>
    <p class="page-subtitle">Find and book the perfect room for your stay</p>

    {{-- Error message --}}
    @if(session('booking_error'))
        <div class="alert alert-danger">{{ session('booking_error') }}</div>
    @endif

    {{-- Filters --}}
    <div class="filter-section">
        <h4>Find Available Rooms</h4>

        <form method="GET" class="row gy-2 gx-2" action="{{ route('rooms.browse') }}">

            <div class="col-md-4">
                <input name="q" value="{{ request('q') }}" 
                       class="form-control" 
                       placeholder="Search (room number, type, description)">
            </div>

            <div class="col-md-3">
                <select name="room_type" class="form-select">
                    <option value="">All types</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" 
                            {{ request('room_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Any status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

        </form>
    </div>

    {{-- No Rooms --}}
    @if($rooms->count() == 0)
        <div class="no-rooms-section">
            <i class="fas fa-search fa-4x mb-3 text-muted"></i>
            <h4>No rooms found</h4>
            <p class="text-muted">Try adjusting your filters or search keywords.</p>
            <a href="{{ route('rooms.browse') }}" class="btn btn-primary">
                <i class="fas fa-refresh me-2"></i>Reset Filters
            </a>
        </div>

    {{-- Rooms Grid --}}
    @else
        <div class="row">

            @foreach($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="card room-type-card h-100">

                    {{-- Thumbnail --}}
                    @if($room->primaryImage)
                        <img src="{{ asset($room->primaryImage->filepath) }}" class="room-type-img">
                    @else
                        <div class="room-type-img bg-light d-flex align-items-center justify-content-center text-muted">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <h5>Room {{ $room->room_number }} ({{ $room->type->name }})</h5>

                        <p>{{ $room->description }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="room-price">₱{{ number_format($room->type->hourly_rate, 2) }}/hr</span>
                            <span class="badge bg-{{ $room->status === 'available' ? 'success' : 'secondary' }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>

                        {{-- BOOKING BUTTON PER GEMINI INSTRUCTION --}}
                        <form action="{{ route('booking.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                            <button class="btn btn-primary w-100">Book</button>
                        </form>

                        {{-- OR view details page --}}
                        {{-- <a href="{{ route('rooms.details', $room->id) }}" class="btn btn-outline-primary w-100 mt-2">View Details</a> --}}
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    @endif

</div>
@endsection
