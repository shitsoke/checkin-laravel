@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between mb-4">
        <h3>🛏️ Room {{ $room->room_number }} — {{ $room->type->name }}</h3>
        <a href="{{ route('admin.rooms') }}" class="btn btn-outline-danger">← Back</a>
    </div>

    {{-- Primary Image --}}
    <div class="mb-4 text-center">
        @php
            $primaryPath = $room->primaryImage->filepath ?? null;
            $primaryUrl = $primaryPath ? asset('storage/'.$primaryPath) : asset('images/default.jpg');
        @endphp
        <img src="{{ $primaryUrl }}" 
             class="img-fluid rounded" style="max-height: 250px;">
    </div>

    {{-- Images --}}
    <div class="card p-3 mb-4">
        <h5>Room Images</h5>
        <div class="row">
            @forelse($room->images as $image)
                @php
                    $imgUrl = $image->filepath ? asset('storage/'.$image->filepath) : asset('images/default.jpg');
                @endphp
                <div class="col-md-4 text-center mb-3">
                    <img src="{{ $imgUrl }}" class="img-fluid rounded" style="height: 180px; object-fit: cover;">
                    <div class="mt-2">
                        @if($image->is_primary)
                            <span class="badge bg-danger mb-1">Primary</span>
                        @else
                            <form method="POST" action="{{ route('admin.rooms.images.primary', $image) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger mb-1">Make Primary</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.rooms.images.delete', $image) }}"
                              onsubmit="return confirm('Delete image?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-muted">No images available</p>
            @endforelse
        </div>
    </div>

    {{-- Description --}}
    <div class="card p-3 mb-4">
        <h5>Description</h5>
        <p>{!! nl2br(e($room->description)) !!}</p>
        <p class="fw-bold text-danger">Rate: ₱{{ number_format($room->type->hourly_rate, 2) }}/hr</p>
    </div>

    {{-- Edit --}}
    <div class="card p-3 mb-4">
        <h5>Edit Room</h5>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
            @csrf @method('PUT')
            <textarea name="description" class="form-control mb-3">{{ $room->description }}</textarea>
            <div class="form-check mb-3">
                <input type="checkbox" name="is_visible" class="form-check-input" {{ $room->is_visible ? 'checked' : '' }}>
                <label class="form-check-label">Visible to customers</label>
            </div>
            <button class="btn btn-danger">Save</button>
        </form>
    </div>

    {{-- Reviews --}}
    <div class="card p-3">
        <h5>Reviews</h5>
        @forelse($room->reviews as $review)
            <div class="border-start border-danger ps-3 mb-3">
                <strong>{{ $review->user->first_name }} {{ $review->user->last_name }}</strong>
                <div>Rating: {{ $review->rating }}/5</div>
                <div>{!! nl2br(e($review->comment)) !!}</div>
            </div>
        @empty
            <p class="text-muted">No reviews yet</p>
        @endforelse
    </div>

</div>
@endsection
