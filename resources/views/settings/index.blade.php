@extends('layouts.app')

@section('title','Settings')

@section('content')
<div class="main-content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div class="w-100">
                <h1 class="page-title text-danger fw-bold"><i class="fas fa-cog me-2"></i>Settings</h1>
                <p class="page-subtitle text-muted">Manage your account preferences and profile settings</p>
            </div>
        </div>

        <div class="settings-card bg-white shadow-sm rounded p-4">

            {{-- Profile Settings --}}
            <div class="settings-section mb-4">
                <h3 class="section-title text-danger fw-semibold mb-3"><i class="fas fa-user me-2"></i>Profile Settings</h3>

                <a href="{{ route('settings.profile') }}" class="btn-settings">
                    <i class="fas fa-user-edit"></i>
                    <div>
                        <div class="fw-bold">Edit Profile</div>
                        <small class="opacity-75">Update your personal information and display name</small>
                    </div>
                </a>

                <a href="{{ route('settings.password') }}" class="btn-settings">
                    <i class="fas fa-key"></i>
                    <div>
                        <div class="fw-bold">Change Password</div>
                        <small class="opacity-75">Update your account password for security</small>
                    </div>
                </a>
            </div>

            {{-- About --}}
            <div class="settings-section mb-4">
                <h3 class="section-title text-danger fw-semibold mb-3"><i class="fas fa-info-circle me-2"></i>About & Information</h3>
                <a href="{{ route('about.index') }}" class="btn-settings">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <div class="fw-bold">About CheckIn</div>
                        <small class="opacity-75">Learn more about our hotel booking system</small>
                    </div>
                </a>
            </div>

            {{-- Preferences placeholders --}}
            <div class="settings-section mb-4">
                <h3 class="section-title text-danger fw-semibold mb-3"><i class="fas fa-bell me-2"></i>Preferences</h3>
                <div class="btn-settings disabled-setting">
                    <i class="fas fa-bell"></i>
                    <div>
                        <div class="fw-bold">Notification Settings</div>
                        <small class="opacity-75">Manage your notifications (Coming Soon)</small>
                    </div>
                </div>

                <div class="btn-settings disabled-setting">
                    <i class="fas fa-moon"></i>
                    <div>
                        <div class="fw-bold">Appearance</div>
                        <small class="opacity-75">Customize theme and display (Coming Soon)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-text bg-light border-start border-4 border-danger p-3 rounded mt-4">
            <p class="mb-0"><strong>Need help?</strong> Contact our support team if you need assistance with any settings or have questions about your account.</p>
        </div>
    </div>
</div>

<style>
:root {
  --primary-color: #dc3545;
  --primary-hover: #c82333;
  --primary-light: rgba(220, 53, 69, 0.1);
}
.page-title { margin-bottom: 5px; font-size: 2rem; }
.page-subtitle { margin-bottom: 20px; }
.settings-card { border-radius: 15px; }
.btn-settings {
  background: white;
  border: 2px solid var(--primary-color);
  color: var(--primary-color);
  padding: 16px 18px;
  border-radius: 10px;
  font-weight: 600;
  text-decoration: none;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  transition: all 0.2s ease;
  margin-bottom: 12px;
  width: 100%;
  text-align: left;
}
.btn-settings:hover {
  background: var(--primary-color);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 5px 12px rgba(220, 53, 69, 0.25);
}
.btn-settings i { font-size: 1.2rem; width: 24px; text-align: center; margin-top: 2px; flex-shrink: 0; }
.btn-settings .fw-bold { font-size: 1.05rem; margin-bottom: 4px; line-height: 1.3; }
.btn-settings small { font-size: 0.95rem; line-height: 1.4; display: block; }
.disabled-setting { cursor: not-allowed; opacity: 0.6; border-style: dashed; }
.section-title { border-bottom: 2px solid var(--primary-light); padding-bottom: 10px; }
.info-text { font-size: 1rem; }
@media (max-width: 768px) {
  .btn-settings { padding: 14px; gap: 10px; }
  .page-title { font-size: 1.7rem; text-align: center; }
  .page-subtitle { text-align: center; }
}
</style>
@endsection

