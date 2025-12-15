@extends('layouts.admin')

@section('title','Manage Users')

@section('content')
<div class="page-wrapper">
    <div class="page-inner container-fluid py-3">
        <h3 class="text-danger fw-bold mb-3">Manage Users</h3>

        {{-- Flash messages --}}
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

        {{-- Filter Form --}}
        <form method="get" class="row gy-2 gx-2 mb-3">
            <div class="col-12 col-md-4">
                <input name="q" value="{{ request('q') }}" class="form-control border-danger" placeholder="Search by name or email">
            </div>
            <div class="col-12 col-md-3">
                <select name="role" class="form-select border-danger">
                    <option value="">All roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ request('role')===$role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="status" class="form-select border-danger">
                    <option value="">Any status</option>
                    <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Active</option>
                    <option value="banned" {{ request('status')==='banned' ? 'selected' : '' }}>Banned</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-danger w-100">Filter</button>
            </div>
        </form>

        {{-- Users Table --}}
        <div class="table-responsive shadow-sm bg-white rounded">
            <table class="table table-bordered table-hover text-center align-middle mb-0">
                <thead>
                    <tr class="table-danger text-white">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        @php
                            $displayName = $u->profile->display_name
                                ?? trim(($u->first_name ?? '').' '.($u->last_name ?? ''))
                                ?: ($u->name ?? $u->email);
                        @endphp
                        <tr class="{{ $u->id % 2 === 0 ? 'table-light' : '' }}">
                            <td>{{ $u->id }}</td>
                            <td>{{ $displayName }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ ucfirst($u->role) }}</td>
                            <td>
                                @if($u->is_banned)
                                    <span class="badge bg-danger">Banned</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                    <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-danger">
                                        View
                                    </a>
                                    @if($u->role === 'admin')
                                        <button class="btn btn-sm btn-secondary" disabled>Protected</button>
                                    @elseif($u->is_banned)
                                        <form method="POST" action="{{ route('admin.users.unban', $u) }}" onsubmit="return confirm('Unban this user?');">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Unban</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.ban', $u) }}" onsubmit="return confirm('Ban this user?');">
                                            @csrf
                                            <button class="btn btn-sm btn-danger">Ban</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

<style>
:root {
    --sidebar-width: 80px;
    --brand-red: #c62828;
    --brand-red-dark: #b71c1c;
}
body {
    background-color: #f8f9fa;
    min-height: 100vh;
}
.page-wrapper { padding: 1.25rem; }
@media (min-width: 992px) {
    .page-wrapper { margin-left: var(--sidebar-width); }
    .page-inner { max-width: 1200px; margin: 0 auto; }
}
@media (max-width: 991.98px) {
    .page-wrapper { margin-left: 0; padding-top: 0.5rem; }
    .page-inner { padding: 0 .5rem; }
}
.table-responsive { overflow-x: auto; }
.table thead th { background-color: var(--brand-red) !important; color: white !important; }
.btn-danger { background-color: var(--brand-red) !important; border-color: var(--brand-red-dark) !important; }
.btn-danger:hover { background-color: var(--brand-red-dark) !important; }
.form-control.border-danger, .form-select.border-danger { border-width: 2px; }
.btn-sm { font-size: 0.85rem; padding: 4px 8px; }
</style>
@endsection
