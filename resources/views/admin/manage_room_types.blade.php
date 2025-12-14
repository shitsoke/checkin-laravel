@extends('layouts.admin')

@section('content')
<div class="page-wrapper">
    <div class="page-inner">
        <div class="container-fluid py-3">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold text-danger mb-0">Manage Room Types</h3>
            </div>

            @if(session('success'))
                <div class="alert alert-info">{{ session('success') }}</div>
            @endif

            <div class="table-responsive shadow-sm">
                <table class="table table-bordered table-hover text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Hourly Rate (₱)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomTypes as $type)
                            <tr class="{{ $type->id % 2 == 0 ? 'table-light' : '' }}">
                                <td>{{ $type->id }}</td>
                                <td>{{ $type->name }}</td>
                                <td>{{ number_format($type->hourly_rate, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.roomtypes.edit', $type) }}" class="btn btn-sm btn-danger">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        @if($roomTypes->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">No room types found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
