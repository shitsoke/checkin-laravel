@extends('layouts.admin')

@section('title','Manage Reviews')

@section('content')
<div class="page-wrapper">
    <div class="page-inner container-fluid py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h3 class="fw-bold text-danger mb-3 mb-md-0">⭐ Reviews Moderation</h3>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger fw-semibold">← Back</a>
        </div>

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

        <div class="alert alert-danger shadow-sm">
            <strong>Note:</strong> "Toggle" hides/unhides a review. "Delete" permanently removes it.
        </div>

        {{-- Filters --}}
        <form method="get" class="row gy-2 gx-2 mb-3 filter-box">
            <div class="col-12 col-md-3">
                <input name="room_id" value="{{ request('room_id') }}" class="form-control" placeholder="Room ID">
            </div>
            <div class="col-12 col-md-3">
                <select name="room_type" class="form-select">
                    <option value="">Any room type</option>
                    @foreach($roomTypes as $rt)
                        <option value="{{ $rt->id }}" {{ request('room_type') == $rt->id ? 'selected' : '' }}>
                            {{ $rt->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="rating" class="form-select">
                    <option value="">Any rating</option>
                    @for($i=5; $i>=1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="visible" class="form-select">
                    <option value="">Any</option>
                    <option value="1" {{ request('visible')==='1' ? 'selected' : '' }}>Visible</option>
                    <option value="0" {{ request('visible')==='0' ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-danger w-100 fw-semibold">Filter</button>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive shadow-sm bg-white rounded">
            <table class="table table-bordered table-hover text-center align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Room Type</th>
                        <th>Room</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Visible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $r)
                        <tr class="{{ $r->id % 2 === 0 ? 'table-light' : '' }}">
                            <td>{{ $r->id }}</td>
                            <td>{{ $r->user->email ?? 'N/A' }}</td>
                            <td>{{ $r->roomType->name ?? 'Hotel' }}</td>
                            <td>
                                @if($r->room)
                                    <a href="{{ route('admin.rooms.show', $r->room) }}" class="text-danger text-decoration-none">View room</a>
                                @else
                                    Overall Hotel
                                @endif
                            </td>
                            <td>{{ (int)$r->rating }}</td>
                            <td class="text-break text-start">{{ $r->comment }}</td>
                            <td>
                                @if($r->is_visible)
                                    <span class="badge bg-danger">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                    <form method="POST" action="{{ route('admin.reviews.toggle', $r) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Toggle</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" onsubmit="return confirm('Delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-muted">No reviews found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

<style>
:root {
    --sidebar-width: 80px;
    --brand-red: #dc3545;
    --brand-red-dark: #b71c1c;
}
body { background-color: #f8f9fa; color: #333; }
.page-wrapper { padding: 1.25rem; }
@media (min-width: 992px) { .page-wrapper { margin-left: var(--sidebar-width); } }
@media (max-width: 991.98px) { .page-wrapper { margin-left: 0; } }
.table-responsive { border-radius: 10px; overflow-x: auto; }
.table thead th { background-color: var(--brand-red) !important; color: #fff !important; }
.filter-box .form-control, .filter-box .form-select { border-width: 2px; border-color: var(--brand-red); }
</style>
@endsection
