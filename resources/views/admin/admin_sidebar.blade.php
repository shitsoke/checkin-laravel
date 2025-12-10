<!-- Sidebar --
@php
    $current_route = Route::currentRouteName();
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --admin-red: #8B0000;
        --admin-red-dark: #a40000;
    }

    .sidebar {
        background-color: var(--admin-red);
        color: #fff;
    }

    .sidebar .offcanvas-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar .nav-link {
        color: #f8f9fa !important;
        border-radius: 6px;
        margin-bottom: 0.4rem;
        transition: background-color 0.2s ease;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background-color: var(--admin-red-dark);
    }

    .logout-btn {
        background-color: transparent;
        color: #fff;
        border: 2px solid #fff;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .logout-btn:hover {
        background-color: #fff;
        color: var(--admin-red-dark);
    }

    /* Desktop */
    @media (min-width: 992px) {
        .offcanvas-lg.sidebar {
            visibility: visible !important;
            transform: none !important;
            position: fixed !important;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            padding-top: 1rem;
        }

        .content {
            margin-left: 260px;
        }

        .navbar-admin-toggle {
            display: none;
        }
    }
</style>

<!-- Mobile toggle -->
<nav class="navbar navbar-dark bg-danger sticky-top d-lg-none navbar-admin-toggle">
    <div class="container-fluid">
        <button class="btn btn-light text-danger fw-semibold"
                data-bs-toggle="offcanvas"
                data-bs-target="#adminSidebar">
            ☰ Menu
        </button>
        <span class="navbar-text text-white fw-bold">CheckIn Admin</span>
    </div>
</nav>

<!-- Sidebar -->
<div class="offcanvas-lg offcanvas-start sidebar text-bg-danger"
     id="adminSidebar">

    <div class="offcanvas-header">
        <h4 class="fw-bold mb-0">CheckIn Admin</h4>
        <button class="btn-close btn-close-white d-lg-none"
                data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">

        <ul class="nav flex-column mb-3">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ $current_route == 'admin.dashboard' ? 'active' : '' }}">
                    🏠 Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.rooms') }}"
                   class="nav-link {{ $current_route == 'admin.rooms' ? 'active' : '' }}">
                    🛏 Manage Rooms
                </a>
            </li>

            <li>
                <a href="{{ route('admin.bookings') }}"
                   class="nav-link {{ $current_route == 'admin.bookings' ? 'active' : '' }}">
                    📘 Manage Bookings
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users') }}"
                   class="nav-link {{ $current_route == 'admin.users' ? 'active' : '' }}">
                    👤 Manage Users
                </a>
            </li>

            <li>
                <a href="{{ route('admin.reviews') }}"
                   class="nav-link {{ $current_route == 'admin.reviews' ? 'active' : '' }}">
                    💬 Manage Reviews
                </a>
            </li>

            <li>
                <a href="{{ route('admin.roomtypes') }}"
                   class="nav-link {{ $current_route == 'admin.roomtypes' ? 'active' : '' }}">
                    🏨 Room Types
                </a>
            </li>
        </ul>

        <!-- Logout -->
        <div class="mt-auto mb-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn logout-btn w-100 fw-semibold">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('adminSidebar');
    const closeButtons = sidebar.querySelectorAll('[data-bs-dismiss="offcanvas"]');

    closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const instance = bootstrap.Offcanvas.getInstance(sidebar);
            if (instance) instance.hide();
        });
    });
});
</script>
