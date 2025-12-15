@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h3 class="fw-bold text-danger mb-3 mb-md-0">
                🖼️ Upload Images for Room {{ $room->room_number }}
                <small class="text-muted">({{ $room->type->name ?? '' }})</small>
            </h3>
            <a href="{{ route('admin.rooms') }}" class="btn btn-outline-danger fw-semibold">← Back</a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-info">{{ session('success') }}</div>
        @endif

        {{-- Upload Form --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-danger text-white fw-bold">Upload New Images</div>
            <div class="card-body bg-white">
                <form method="POST" action="{{ route('admin.rooms.images.store', $room->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">Select images (JPG, PNG, WEBP) — max 3MB each</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="form-control border-danger">
                    </div>
                    <button class="btn btn-danger fw-semibold px-4">⬆️ Upload</button>
                </form>
            </div>
        </div>

        {{-- Existing Images --}}
        <h5 class="fw-bold text-danger mb-3">📂 Existing Images</h5>
        <div class="row">
            @foreach($room->images as $image)
                <div class="col-6 col-sm-4 col-md-3 mb-4">
                    <div class="card border-0 shadow-sm img-card">
                        <img src="{{ asset('storage/' . $image->filepath) }}"
                             class="card-img-top"
                             style="height: 180px; object-fit: cover; border-bottom: 3px solid {{ $image->is_primary ? '#dc3545' : '#dee2e6' }};">
                        <div class="card-body text-center">
                            @if($image->is_primary)
                                <span class="badge bg-danger mb-2">Primary</span>
                            @endif
                            <div class="d-flex justify-content-center gap-2">
                                @if(!$image->is_primary)
                                    <form method="POST" action="{{ route('admin.rooms.images.primary', $image->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Make Primary</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.rooms.images.delete', $image->id) }}" onsubmit="return confirm('Delete image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
