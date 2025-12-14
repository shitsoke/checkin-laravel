<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Admin')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --admin-red: #8B0000;
        --admin-red-dark: #a40000;
    }

    .sidebar {
        background-color: var(--admin-red);
        width: 250px;
    }

    @media (min-width: 992px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        .admin-content {
            margin-left: 250px;
            padding: 30px;
        }
    }
</style>
</head>

<body>

{{-- Mobile toggle --}}
<nav class="navbar navbar-dark bg-danger d-lg-none sticky-top">
    <div class="container-fluid">
        <button class="btn btn-light text-danger"
                data-bs-toggle="offcanvas"
                data-bs-target="#adminSidebar">
            ☰ Menu
        </button>
        <span class="navbar-text text-white fw-bold">CheckIn Admin</span>
    </div>
</nav>

@include('admin.admin_sidebar')

<main class="admin-content">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
