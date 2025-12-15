@extends('layouts.app')

@section('title','Manage Bookings')
@section('content')
<div class="main-content">
    <div class="container-fluid mt-4 px-4">

        {{-- Header --}}
        <div class="header-section">
            <div class="header-content">
                <h3><i class="fas fa-calendar-check"></i> My Bookings</h3>
                <p class="page-subtitle">Manage and view all your booking history</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="filter-section">
            <h5><i class="fas fa-search me-2"></i>Search & Filter Bookings</h5>

            <form method="get" class="row g-2">
                <div class="col-md-3">
                    <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search room, type, payment">
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Any status</option>
                        @foreach(['reserved','confirmed','ongoing','checked_out','canceled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input name="room" value="{{ request('room') }}" class="form-control" placeholder="Room #">
                </div>

                <div class="col-md-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>

                <div class="col-md-1">
                    <button class="btn btn-light w-100" style="color:#dc3545;font-weight:600">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Hours</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td>{{ $b->room->room_number }}</td>
                            <td>{{ $b->room->type->name }}</td>
                            <td>{{ $b->start_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $b->end_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $b->hours }}</td>
                            <td>₱{{ number_format($b->total_amount,2) }}</td>
                            <td>
                                @php
                                    $badge = match($b->status) {
                                        'confirmed' => 'bg-success',
                                        'reserved' => 'bg-warning text-dark',
                                        'ongoing' => 'bg-primary',
                                        'checked_out' => 'bg-info',
                                        'canceled' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $b->status }}</span>
                            </td>
                            <td>{{ $b->payment_method }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('bookings.show',$b->id) }}" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>

                                    @if(in_array($b->status,['confirmed','checked_out','ongoing']))
                                        <a href="{{ route('receipt.show',$b) }}?download=pdf" class="btn btn-sm btn-danger">
                                            <i class="fas fa-download me-1"></i>Receipt
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-danger mb-3"></i>
                                <p class="text-muted">No bookings found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

{{-- STYLES --}}
<style>
:root {
    --primary-color:#dc3545;
    --primary-hover:#c82333;
}

/* MAIN LAYOUT */
.main-content {
    margin-left:80px; /* SIDEBAR WIDTH */
    padding:20px;
    min-height:100vh;
    transition:.3s;
}

/* HEADER */
.header-section h3 {
    color:var(--primary-color);
    font-weight:800;
}

.page-subtitle {
    color:#6c757d;
}

/* FILTER */
.filter-section {
    background:linear-gradient(135deg,#dc3545,#c82333);
    color:#fff;
    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
}

.filter-section h5 {
    margin-bottom:15px;
}

/* TABLE */
.table-responsive {
    overflow-x:auto;
    -webkit-overflow-scrolling:touch;
}

.table {
    background:#fff;
    min-width:900px;
}

.table thead th {
    background:#dc3545 !important;
    color:#fff !important;
    border-color:#dc3545 !important;
    white-space:nowrap;
}

.table tbody tr:hover {
    background:rgba(220,53,69,.08);
}

/* ACTION BUTTONS */
.action-buttons {
    display:flex;
    flex-direction:column;
    gap:6px;
    min-width:140px;
}

/* OPTIONAL: limit width on very large screens */
.container-fluid {
    max-width:1600px;
}

/* MOBILE FIXES */
@media (max-width:768px) {
    .main-content {
        margin-left:0 !important;
        padding:15px;
        padding-top:70px;
    }

    .table {
        min-width:950px;
    }
}

@media (max-width:480px) {
    .table {
        min-width:1000px;
    }
}
</style>
@endsection
