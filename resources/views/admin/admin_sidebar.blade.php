<style>
    :root {
        --admin-red: #8B0000;
        --admin-red-dark: #a40000;
    }

    /* SIDEBAR BACKGROUND */
    .sidebar {
        background-color: var(--admin-red) !important;
        color: #ffffff !important;
        width: 250px;
    }

    /* LINKS */
    .sidebar .nav-link {
        color: #ffffff !important;
        font-weight: 500;
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 5px;
        transition: background 0.2s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: var(--admin-red-dark);
        color: #ffffff !important;
    }

    /* LOGOUT BUTTON */
    .logout-btn {
        background-color: transparent;
        color: #ffffff;
        border: 2px solid #ffffff;
        font-weight: 600;
    }

    .logout-btn:hover {
        background-color: #ffffff;
        color: var(--admin-red-dark);
    }

    /* DESKTOP: FIX SIDEBAR ON LEFT */
    @media (min-width: 992px) {
        .offcanvas-lg.sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            visibility: visible !important;
            transform: none !important;
        }

        .main-content {
            margin-left: 260px;
        }
    }
</style>

@php
    $current_route = Route::currentRouteName();
@endphp

<div class="offcanvas-lg offcanvas-start sidebar text-bg-danger" id="adminSidebar">

    <div class="offcanvas-header">
        <h4 class="fw-bold mb-0">CheckIn Admin</h4>
        <button class="btn-close btn-close-white d-lg-none"
                data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">

        <ul class="nav flex-column mb-3">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ $current_route === 'admin.dashboard' ? 'active' : '' }}">
                    🏠 Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.rooms') }}"
                   class="nav-link {{ $current_route === 'admin.rooms' ? 'active' : '' }}">
                    🛏 Manage Rooms
                </a>
            </li>

            <li>
                <a href="{{ route('admin.bookings') }}"
                   class="nav-link {{ $current_route === 'admin.bookings' ? 'active' : '' }}">
                    📘 Manage Bookings
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users') }}"
                   class="nav-link {{ $current_route === 'admin.users' ? 'active' : '' }}">
                    👤 Manage Users
                </a>
            </li>

            <li>
                <a href="{{ route('admin.reviews') }}"
                   class="nav-link {{ $current_route === 'admin.reviews' ? 'active' : '' }}">
                    💬 Manage Reviews
                </a>
            </li>

            <li>
                <a href="{{ route('admin.roomtypes') }}"
                   class="nav-link {{ $current_route === 'admin.roomtypes' ? 'active' : '' }}">
                    🏨 Room Types
                </a>
            </li>
        </ul>

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
