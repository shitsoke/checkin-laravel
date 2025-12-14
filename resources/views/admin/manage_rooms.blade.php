@extends('layouts.admin')

@section('content')
<div class="page-wrapper">
    <div class="page-inner">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Manage Rooms</h3>
            </div>

            @if(session('success'))
                <div class="alert alert-info">{{ session('success') }}</div>
            @endif

            <!-- Add New Room -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Add New Room</h5>
                    <form method="POST" action="{{ route('admin.rooms.store') }}" class="row g-2">
                        @csrf
                        <div class="col-12 col-md-3">
                            <input name="room_number" class="form-control" placeholder="Room Number" value="{{ old('room_number') }}">
                            @error('room_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-12 col-md-3">
                            <select name="room_type_id" class="form-select" required>
                                <option value="" disabled selected>Select type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('room_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} (₱{{ number_format($type->hourly_rate, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-12 col-md-3">
                            <input name="description" class="form-control" placeholder="Description (optional)" value="{{ old('description') }}">
                        </div>

                        <div class="col-6 col-md-1">
                            <div class="form-check mt-2">
                                <input type="checkbox" name="is_visible" class="form-check-input" id="isVisible" {{ old('is_visible', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isVisible">Visible</label>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Add Room</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filter Rooms</h5>
                    <form method="GET" class="row g-2">
                        <div class="col-12 col-md-3">
                            <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search...">
                        </div>

                        <div class="col-12 col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Any status</option>
                                @foreach(['Available','Reserved','Occupied','Maintenance'] as $s)
                                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <select name="room_type" class="form-select">
                                <option value="">All types</option>
                                @foreach($types as $t)
                                    <option value="{{ $t->id }}" {{ request('room_type') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
                            <select name="visible" class="form-select">
                                <option value="">Visibility</option>
                                <option value="1" {{ request('visible') === '1' ? 'selected' : '' }}>Visible</option>
                                <option value="0" {{ request('visible') === '0' ? 'selected' : '' }}>Hidden</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-outline-danger w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Room List -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Room List</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-danger text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Room #</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Visible</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $r)
                                    <tr>
                                        <td>{{ $r->id }}</td>
                                        <td>{{ $r->room_number }}</td>
                                        <td>{{ $r->type->name ?? '' }}</td>
                                        <td>{{ $r->status }}</td>
                                        <td>{{ $r->is_visible ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <form method="POST" action="{{ route('admin.rooms.toggle', $r) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                                        {{ $r->is_visible ? 'Hide' : 'Show' }}
                                                    </button>
                                                </form>
                                                <a class="btn btn-sm btn-secondary" href="{{ route('admin.roomtypes') }}">Upload Images</a>
                                                <a class="btn btn-sm btn-info" href="#">View / Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No rooms found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div><!-- /.container -->
    </div><!-- /.page-inner -->
</div><!-- /.page-wrapper -->
@endsection
