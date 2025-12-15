@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h3 class="fw-bold text-danger mb-3 mb-md-0">
            <i class="fas fa-bed me-2"></i>Browse Our Rooms
        </h3>
        <p class="text-muted mb-0">Find and book the perfect room for your stay</p>
    </div>

    {{-- Filters --}}
    <div class="card mb-4 p-3">
        <h5>Find Available Rooms</h5>
        <form method="GET" class="row g-2" action="{{ route('rooms.browse') }}">
            <div class="col-md-5">
                <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search (room number, type, description)">
            </div>

            <div class="col-md-3">
                <select name="room_type" class="form-select">
                    <option value="">All types</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ request('room_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Any status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-danger w-100">Filter</button>
            </div>
        </form>
    </div>

    {{-- No Rooms --}}
    @if($rooms->count() == 0)
        <div class="text-center text-muted py-5">
            <i class="fas fa-search fa-4x mb-3"></i>
            <h4>No rooms found</h4>
            <p>Try adjusting your filters or search keywords.</p>
            <a href="{{ route('rooms.browse') }}" class="btn btn-danger"><i class="fas fa-refresh me-2"></i>Reset Filters</a>
        </div>

    {{-- Rooms Grid --}}
    @else
        <div class="row">
            @foreach($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">

                    {{-- Room Image --}}
                    @php
                        $primaryPath = $room->primaryImage->filepath ?? $room->images->first()->filepath ?? null;
                    @endphp
                    @if($primaryPath)
                        <img src="{{ asset('storage/' . $primaryPath) }}" alt="{{ $room->room_number }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif

                    {{-- Card Body --}}
                    <div class="card-body d-flex flex-column">
                        <h5>Room {{ $room->room_number }} ({{ $room->type->name }})</h5>
                        <p class="text-muted">{{ $room->description }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-danger">₱{{ number_format($room->type->hourly_rate, 2) }}/hr</span>
                            @if($room->status === 'available')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($room->status) }}</span>
                            @endif
                        </div>

                        <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-outline-primary mt-auto w-100">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
