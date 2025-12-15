@extends('layouts.app')

@section('title','Change Password')

@section('content')
<div class="main-content">
    <div class="container mt-3 mt-md-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div class="flex-grow-1">
                <h1 class="page-title"><i class="fas fa-key me-2"></i>Change Password</h1>
                <p class="page-subtitle">Secure your account with a new password</p>
            </div>
            <a href="{{ $returnTo }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>

        <div class="password-card">
            @if(session('success'))
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-start">
                    <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                    <div>
                        <div class="fw-semibold mb-1">Please fix the following:</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update.password') }}" id="passwordForm">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">Current Password</label>
                    <div class="input-group">
                        <input name="current_password" type="password" class="form-control" id="currentPassword" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('currentPassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <input name="password" type="password" class="form-control" id="newPassword" required minlength="8" oninput="checkPasswordStrength();checkPasswordMatch();">
                        <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength mt-2">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement invalid" id="reqLength">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            At least 8 characters
                        </div>
                        <div class="requirement invalid" id="reqUpper">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            One uppercase letter
                        </div>
                        <div class="requirement invalid" id="reqLower">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            One lowercase letter
                        </div>
                        <div class="requirement invalid" id="reqNumber">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            One number
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <input name="password_confirmation" type="password" class="form-control" id="confirmPassword" required minlength="8" oninput="checkPasswordMatch()">
                        <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="mt-2" id="passwordMatch"></div>
                </div>

                <div class="d-flex gap-2 mt-4 flex-column flex-md-row">
                    <button type="submit" class="btn btn-primary order-2 order-md-1">
                        <i class="fas fa-save me-2"></i>Change Password
                    </button>
                    <a href="{{ $returnTo }}" class="btn btn-secondary order-1 order-md-2">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
:root {
  --primary-color: #dc3545;
  --primary-hover: #c82333;
  --primary-light: rgba(220, 53, 69, 0.1);
}

body {
  background-color: #f8f9fa;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.password-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  padding: 25px;
  margin-top: 15px;
}

.page-title {
  color: var(--primary-color);
  font-weight: 700;
  margin-bottom: 5px;
  font-size: 1.8rem;
}

.page-subtitle {
  color: #666;
  font-size: 1rem;
  margin-bottom: 25px;
}

.sidebar-toggle, .toggle-btn {
  display: none !important;
}

.input-group { position: relative; }

.password-toggle {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #6c757d;
  cursor: pointer;
  z-index: 3;
}

.password-toggle:hover { color: var(--primary-color); }

.btn-primary {
  background: var(--primary-color);
  border: none;
  height: 45px;
  font-weight: 600;
  color: white;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 20px;
  width: 100%;
}

.btn-primary:hover {
  background: var(--primary-hover);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

.btn-secondary {
  background: #6c757d;
  border: none;
  height: 45px;
  font-weight: 600;
  color: white;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 20px;
  width: 100%;
}

.btn-secondary:hover {
  background: #545b62;
  transform: translateY(-2px);
}

.btn-back {
  background: var(--primary-color);
  border: none;
  color: white;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
  margin-bottom: 20px;
  font-size: 0.9rem;
}

.btn-back:hover {
  background: var(--primary-hover);
  color: white;
  transform: translateY(-2px);
}

.form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.alert-info {
  border-left: 4px solid var(--primary-color);
  background: var(--primary-light);
}

.alert-danger {
  border-left: 4px solid var(--primary-color);
  background: var(--primary-light);
}

.form-label {
  font-weight: 600;
  color: #333;
  margin-bottom: 8px;
}

.password-strength {
  height: 4px;
  background: #e9ecef;
  border-radius: 2px;
  margin-top: 5px;
  overflow: hidden;
}

.password-strength-bar {
  height: 100%;
  width: 0%;
  transition: all 0.3s ease;
  border-radius: 2px;
}

.password-requirements {
  font-size: 0.875rem;
  color: #666;
  margin-top: 10px;
}

.requirement { display: flex; align-items: center; gap: 5px; margin-bottom: 3px; }
.requirement.valid { color: #28a745; }
.requirement.invalid { color: #666; }

@media (max-width: 768px) {
  .container { padding: 10px; max-width: 100%; }
  .password-card { padding: 20px; margin-top: 10px; border-radius: 12px; }
  .page-title { font-size: 1.5rem; text-align: center; }
  .page-subtitle { font-size: 0.95rem; text-align: center; margin-bottom: 20px; }
  .btn-back { padding: 8px 14px; font-size: 0.85rem; margin-bottom: 15px; }
  .btn-primary, .btn-secondary { height: 50px; font-size: 1rem; padding: 0 15px; }
  .form-control { font-size: 16px; padding: 12px 15px; }
  .alert { padding: 12px 15px; font-size: 0.9rem; }
  .password-requirements { font-size: 0.8rem; }
  .d-flex.justify-content-between.align-items-start.mb-4 { flex-direction: row; text-align: left; gap: 15px; position: relative; }
  .btn-back { position: absolute; top: 0; right: 0; margin-bottom: 0; align-self: flex-start; }
  .page-title, .page-subtitle { text-align: left; padding-right: 100px; }
  .d-flex.gap-2 { flex-direction: column; }
  .d-flex.gap-2 .btn { margin-bottom: 10px; }
  .d-flex.gap-2 .btn:last-child { margin-bottom: 0; }
}

@media (max-width: 576px) {
  .container { padding: 8px; }
  .password-card { padding: 15px; border-radius: 10px; }
  .page-title { font-size: 1.3rem; }
  .page-subtitle { font-size: 0.9rem; }
  .btn-back { padding: 6px 12px; font-size: 0.8rem; }
  .form-control { padding: 10px 12px; }
  .d-flex.gap-2 { gap: 10px !important; }
  .mb-4 { margin-bottom: 1rem !important; }
  .page-title, .page-subtitle { padding-right: 90px; }
  .btn-back { padding: 5px 10px; font-size: 0.75rem; }
}

@media (max-width: 480px) {
  .page-title { font-size: 1.2rem; }
  .page-subtitle { font-size: 0.85rem; }
  .password-card { padding: 12px; }
  .btn-primary, .btn-secondary { height: 45px; font-size: 0.9rem; }
  .page-title, .page-subtitle { padding-right: 80px; }
  .btn-back { padding: 4px 8px; font-size: 0.7rem; }
}
</style>

<script>
function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  const button = input.parentNode.querySelector('.password-toggle');
  const icon = button.querySelector('i');

  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}

function checkPasswordStrength() {
  const password = document.getElementById('newPassword').value;
  const strengthBar = document.getElementById('passwordStrengthBar');

  const requirements = {
    length: document.getElementById('reqLength'),
    upper: document.getElementById('reqUpper'),
    lower: document.getElementById('reqLower'),
    number: document.getElementById('reqNumber')
  };

  let strength = 0;

  if (password.length >= 8) {
    requirements.length.classList.add('valid');
    requirements.length.classList.remove('invalid');
    strength += 25;
  } else {
    requirements.length.classList.remove('valid');
    requirements.length.classList.add('invalid');
  }

  if (/[A-Z]/.test(password)) {
    requirements.upper.classList.add('valid');
    requirements.upper.classList.remove('invalid');
    strength += 25;
  } else {
    requirements.upper.classList.remove('valid');
    requirements.upper.classList.add('invalid');
  }

  if (/[a-z]/.test(password)) {
    requirements.lower.classList.add('valid');
    requirements.lower.classList.remove('invalid');
    strength += 25;
  } else {
    requirements.lower.classList.remove('valid');
    requirements.lower.classList.add('invalid');
  }

  if (/[0-9]/.test(password)) {
    requirements.number.classList.add('valid');
    requirements.number.classList.remove('invalid');
    strength += 25;
  } else {
    requirements.number.classList.remove('valid');
    requirements.number.classList.add('invalid');
  }

  strengthBar.style.width = strength + '%';

  if (strength < 50) {
    strengthBar.style.background = '#dc3545';
  } else if (strength < 75) {
    strengthBar.style.background = '#ffc107';
  } else {
    strengthBar.style.background = '#28a745';
  }
}

function checkPasswordMatch() {
  const newPassword = document.getElementById('newPassword').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  const matchDiv = document.getElementById('passwordMatch');

  if (confirmPassword === '') {
    matchDiv.innerHTML = '';
    return;
  }

  if (newPassword === confirmPassword) {
    matchDiv.innerHTML = '<small class="text-success"><i class="fas fa-check-circle me-1"></i>Passwords match</small>';
  } else {
    matchDiv.innerHTML = '<small class="text-danger"><i class="fas fa-times-circle me-1"></i>Passwords do not match</small>';
  }
}
</script>
@endsection


