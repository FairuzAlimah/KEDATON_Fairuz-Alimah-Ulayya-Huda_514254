<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm sticky-top" style="background-color:  #FFFF9E;">
    <div class="container-fluid">

        <!-- Tombol buka sidebar -->
        <button class="btn me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu"
            style="background-color: #B22222; color:  #FFFF9E;">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Judul (sesuaikan dengan halaman aktif) -->
        @php
            $currentRoute = Route::currentRouteName();
            $titles = [
                'home' => ['icon' => null, 'label' => 'KEDATON'],
                'map' => ['icon' => 'fa-map-location-dot', 'label' => 'Peta Sebaran UMKM di Bandar Lampung'],
                'table' => ['icon' => 'fa-table', 'label' => 'Tabel Data UMKM di Bandar Lampung'],
                'kedaton' => ['icon' => 'fa-newspaper', 'label' => 'Terkait Aplikasi dan UMKM di Bandar Lampung'],
                'default' => ['icon' => null, 'label' => $title],
            ];
            $page = $titles[$currentRoute] ?? $titles['default'];
        @endphp
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            @if ($currentRoute === 'home')
                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo Kedaton" class="logo-bounce" width="40"
                    height="40">
            @elseif($page['icon'])
                <i class="fa-solid {{ $page['icon'] }} fa-fw fa-lg" style="color: #B22222;"></i>
            @endif
            <span class="fw-bold" style="font-weight: 800 !important; color: #B22222;">{{ $page['label'] }}</span>
        </a>




        <!-- User info -->
        <div class="d-flex ms-auto">
            @auth
                <div class="dropdown">
                    <button class="btn user-btn fw-bold" type="button" id="userDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-solid fa-user"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="px-3">
                                @csrf
                                <button class="btn btn-danger w-100" type="submit">
                                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="btn user-btn fw-bold">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </a>
            @endguest
        </div>
    </div>
</nav>

<!-- Sidebar Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu"
    style="width: 220px; background-color: #B22222; color: #FFFF9E;">
    <div class="offcanvas-header border-bottom border-light">
        <h5 class="offcanvas-title"><i class="fa-solid fa-bars"></i> Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-2">
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link sidebar-link fw-bold" href="{{ route('home') }}">
                    <i class="fa-solid fa-house" style="color: #FFFF9E;"></i> Halaman Utama
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link sidebar-link fw-bold" href="{{ route('map') }}">
                    <i class="fa-solid fa-map-location-dot" style="color: #FFFF9E;"></i> Peta Interaktif
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link sidebar-link fw-bold" href="{{ route('table') }}">
                    <i class="fa-solid fa-table" style="color: #FFFF9E;"></i> Data Tabel
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link sidebar-link fw-bold" href="{{ route('kedaton') }}">
                    <i class="fa-solid fa-newspaper" style="color: #FFFF9E;"></i> Tentang Kami
                </a>
            </li>

            @auth
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link fw-bold" data-bs-toggle="collapse" href="#dataSubmenu" role="button"
                        aria-expanded="false">
                        <i class="fa-solid fa-database" style="color: #FFFF9E;"></i> Data
                    </a>
                    <div class="collapse ps-3" id="dataSubmenu">
                        <ul class="list-unstyled">
                            <li>
                                <a class="nav-link" href="{{ route('api.points') }}" target="_blank"
                                    style="color: #FFFF9E;">
                                    Titik UMKM
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endauth


        </ul>
    </div>
</div>

<!-- Style tambahan -->
<style>
    .sidebar-link {
        color: #FFFF9E;
        transition: 0.3s;
    }

    .sidebar-link:hover {
        background-color: #FFFF9E;
        color: #B22222 !important;
        border-radius: 8px;
    }

    .sidebar-link i {
        margin-right: 8px;
    }

    .sidebar-link:hover i {
        color: #B22222 !important;
    }

    .sidebar-link[data-bs-toggle="collapse"] {
        color: #FFFF9E !important;
    }

    .sidebar-link[data-bs-toggle="collapse"]:hover {
        background-color: #FFFF9E;
        color: #B22222 !important;
        border-radius: 8px;
    }

    .sidebar-link[data-bs-toggle="collapse"]:hover i {
        color: #B22222 !important;
    }

    .user-btn {
        background-color: #B22222;
        color: #FFFF9E;
        border: none;
        transition: 0.3s;
    }

    .user-btn:hover {
        background-color: #FFFF9E;
        color: #B22222;
    }

    .logo-bounce {
        animation: bounce 2s infinite;

    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }
</style>
