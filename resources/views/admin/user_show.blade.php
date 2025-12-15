@extends('layouts.admin')

@section('title','View User')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h3 class="fw-bold text-danger mb-3 mb-md-0">
            <i class="bi bi-person-circle me-2"></i> User Profile
        </h3>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-danger fw-semibold">
            &larr; Back to Users
        </a>
    </div>

    {{-- Profile Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-danger text-white fw-bold">
            User Information
        </div>
        <div class="card-body p-4 bg-white">
            <div class="row g-4 align-items-start">
                {{-- Avatar --}}
                <div class="col-md-4 text-center">
                    @php
                        $avatar = $user->profile->avatar ?? null;
                        $avatarUrl = $avatar ? asset('storage/'.$avatar) : null;
                    @endphp

                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}"
                             class="img-fluid rounded-circle border border-3 border-danger mb-3"
                             style="width:180px;height:180px;object-fit:cover;"
                             alt="Avatar">
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center border border-2 border-danger rounded-circle mb-3"
                             style="width:180px;height:180px;">
                            <span class="text-muted">No Avatar</span>
                        </div>
                    @endif

                    @if($user->is_banned)
                        <span class="badge bg-danger px-3 py-2">Banned</span>
                    @else
                        <span class="badge bg-success px-3 py-2">Active</span>
                    @endif
                </div>

                {{-- Details --}}
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-danger" style="width:25%;">Full Name:</th>
                                    <td class="fw-semibold">
                                        {{ trim(($user->first_name ?? '').' '.($user->middle_name ?? '').' '.($user->last_name ?? '')) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-danger">Display Name:</th>
                                    <td>{{ $user->profile->display_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-danger">Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th class="text-danger">Phone:</th>
                                    <td>{{ $user->profile->phone ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-danger">Address:</th>
                                    <td>{!! nl2br(e($user->profile->address ?? '')) !!}</td>
                                </tr>
                                <tr>
                                    <th class="text-danger">Registered:</th>
                                    <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card { border-radius: 10px; }
.card-header { border-top-left-radius: 10px !important; border-top-right-radius: 10px !important; }
.badge { font-size: 0.9rem; }
@media (min-width: 992px) {
    .admin-content .container-fluid { max-width: 1200px; }
}
</style>
@endsection


