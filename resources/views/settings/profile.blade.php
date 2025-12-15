@extends('layouts.app')

@section('title','Edit Profile')

@section('content')
<div class="main-content">
    <div class="container mt-4 mb-5">
        <div class="position-relative mb-3">
            <div>
                <h1 class="page-title text-danger fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Profile</h1>
                <p class="text-muted mb-2">Update your personal information and profile settings</p>
            </div>
            <a href="{{ route('settings.index') }}" class="btn btn-danger btn-sm position-absolute top-0 end-0">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('settings.update.profile') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div class="avatar-container position-relative d-inline-block">
                                @php
                                    $avatar = $user->profile->avatar ?? null;
                                    $avatarUrl = $avatar ? asset('storage/'.$avatar) : null;
                                @endphp
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" class="avatar-preview rounded-circle border border-danger" style="width:140px;height:140px;object-fit:cover;">
                                @else
                                    <div class="avatar-placeholder rounded-circle border border-danger d-flex align-items-center justify-content-center" style="width:140px;height:140px;">
                                        <span class="fs-1 text-danger">👤</span>
                                    </div>
                                @endif
                                <label for="avatar-upload" class="avatar-upload btn btn-danger btn-sm rounded-circle position-absolute bottom-0 end-0" style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" name="avatar" id="avatar-upload" class="d-none" accept="image/*">
                            </div>
                            <small class="text-muted d-block mt-2">JPG/PNG/WebP up to 2MB</small>
                        </div>

                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input class="form-control" value="{{ $user->email }}" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Display Name</label>
                                <input name="display_name" class="form-control" value="{{ old('display_name', $user->profile->display_name ?? '') }}" placeholder="How your name will appear to others">
                                <small class="text-muted">Optional - leave blank to use your real name</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="text-danger fw-semibold mb-3"><i class="fas fa-user me-2"></i>Personal Information</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input name="middle_name" class="form-control" value="{{ old('middle_name', $user->middle_name) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                        </div>
                    </div>

                    <h5 class="text-danger fw-semibold mb-3"><i class="fas fa-phone me-2"></i>Contact Information</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input name="phone" class="form-control" value="{{ old('phone', $user->profile->phone ?? '') }}" placeholder="Your contact number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input name="address" class="form-control" value="{{ old('address', $user->profile->address ?? '') }}" placeholder="Your current address">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-danger"><i class="fas fa-save me-2"></i>Save Changes</button>
                        <a href="{{ route('settings.index') }}" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm" id="password-form">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-key me-2"></i>Change Password
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.update.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                    </div>
                    <button class="btn btn-danger w-100">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.page-title{ font-size: 1.8rem; }
.avatar-placeholder{ background: rgba(220,53,69,0.08); }
</style>
@endsection

