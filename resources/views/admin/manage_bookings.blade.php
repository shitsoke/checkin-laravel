@extends('layouts.admin')

@section('title','Manage Bookings')

@section('content')
<div class="page-wrapper">
    <div class="page-inner container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
            <h3 class="fw-bold mb-3 mb-sm-0 text-danger">Manage Bookings</h3>
        </div>

        <!-- Success/Error messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        <!-- Filter Form -->
        <form method="GET" class="row gy-2 gx-2 mb-3">
            <div class="col-12 col-sm-6 col-md-3">
                <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by email or room #">
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select">
                    <option value="">Any status</option>
                    @foreach(['reserved','confirmed','ongoing','checked_out','canceled'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <input name="room" value="{{ request('room') }}" class="form-control" placeholder="Room ID">
            </div>
            <div class="col-6 col-md-2">
                <input name="from_date" type="date" value="{{ request('from_date') }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <input name="to_date" type="date" value="{{ request('to_date') }}" class="form-control">
            </div>
            <div class="col-12 col-md-1">
                <button class="btn btn-danger w-100">Filter</button>
            </div>
        </form>

        <!-- Bookings Table -->
        <div class="card shadow-sm">
            <div class="card-body p-2 p-md-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle mb-0">
                        <thead>
                            <tr class="table-danger text-white">
                                <th>ID</th>
                                <th>User</th>
                                <th>Room</th>
                                <th>Period</th>
                                <th>Hours</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr class="{{ $b->status === 'canceled' ? 'table-light' : '' }}">
                                    <td>{{ $b->id }}</td>
                                    <td>
                                        {{ $b->user->email }}<br>
                                        {{ $b->user->first_name }} {{ $b->user->last_name }}
                                    </td>
                                    <td>{{ $b->room->room_number }}</td>
                                    <td>{{ $b->start_time->format('Y-m-d H:i') }} → {{ $b->end_time->format('Y-m-d H:i') }}</td>
                                    <td>{{ $b->hours }}</td>
                                    <td>₱{{ number_format($b->total_amount,2) }}</td>
                                    <td>
                                        <span class="badge bg-danger text-light">{{ ucfirst($b->status) }}</span>
                                        @if($b->receipt_sent)
                                            <span class="badge bg-success">Receipt sent</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <form method="POST" action="{{ route('admin.bookings.update', $b) }}" class="d-inline">
                                                @csrf
                                                <select name="action" class="form-select form-select-sm mb-1">
                                                    <option value="">Change status...</option>
                                                    <option value="confirmed">Confirm</option>
                                                    <option value="ongoing">Mark ongoing</option>
                                                    <option value="checked_out">Check-out</option>
                                                    <option value="canceled">Cancel</option>
                                                </select>
                                                <button class="btn btn-sm btn-outline-danger w-100">Apply</button>
                                            </form>
                                            <a href="{{ route('receipt.show', $b) }}" class="btn btn-sm btn-danger">View Receipt</a>
                                            <a href="{{ route('receipt.show', $b) }}?download=pdf" class="btn btn-sm btn-dark">Download PDF</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8">No bookings found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-wrapper { padding: 1.25rem; }
@media (min-width: 992px) { .page-inner { max-width: 1200px; margin: 0 auto; } }
.table-danger { background-color: #c62828 !important; color: white; }
.btn-outline-danger { color: #c62828; border-color: #c62828; }
.btn-outline-danger:hover { background-color: #c62828; color: white; }
</style>
@endsection
