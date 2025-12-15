@extends('layouts.admin')

@section('content')
<div class="page-wrapper">
    <div class="page-inner">
        <div class="container py-3">

            <h3 class="fw-bold text-danger mb-3">Edit Room Type</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.roomtypes.update', $roomType) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Room Type Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $roomType->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hourly Rate (₱)</label>
                    <input type="number" step="0.01" name="hourly_rate" class="form-control" value="{{ old('hourly_rate', $roomType->hourly_rate) }}" required>
                </div>

                <button class="btn btn-primary">Update Room Type</button>
                <a href="{{ route('admin.roomtypes') }}" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>
@endsection
