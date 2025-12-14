@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-wrapper">
    <div class="page-inner">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-danger mb-0">Admin Dashboard</h3>
        </div>

        <div class="text-center text-muted mb-4">
            <p class="mb-1">
                Welcome back,
                <strong class="text-danger">{{ auth()->user()->first_name ?? 'Admin' }}</strong>!
            </p>
            <small>Use the sidebar to manage rooms, users, and bookings.</small>
        </div>

        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card text-center border-success shadow-sm p-3">
                    <h5 class="text-success">Available Rooms</h5>
                    <h2 class="fw-bold">{{ $available_rooms }}</h2>
                    <p class="text-muted mb-0">Rooms ready for booking</p>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card text-center border-warning shadow-sm p-3">
                    <h5 class="text-warning">Reserved Rooms</h5>
                    <h2 class="fw-bold">{{ $reserved_rooms }}</h2>
                    <p class="text-muted mb-0">Awaiting guest check-in</p>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card text-center border-danger shadow-sm p-3">
                    <h5 class="text-danger">Confirmed / Ongoing</h5>
                    <h2 class="fw-bold">{{ $ongoing_rooms }}</h2>
                    <p class="text-muted mb-0">Currently occupied rooms</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
