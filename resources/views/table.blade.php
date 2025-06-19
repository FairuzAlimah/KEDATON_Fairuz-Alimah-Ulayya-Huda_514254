@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Indonesian Color Palette */
        :root {
            --merah-primary: #b81414;
            --merah-secondary: #ea0000;
            --kuning-primary: #FFFF9E;
            --kuning-secondary: #ffffdf;
            --putih: #ffffff;
            --abu-terang: #f8fafc;
            --abu-gelap: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--kuning-secondary) 0%, #fff7ed 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
        }

        .app {
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Page Header */
        .page-header {
            background: var(--merah-primary);
            color: var(--kuning-primary);
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23FFFF9E' fill-opacity='0.1'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zm0 0c0 11.046 8.954 20 20 20s20-8.954 20-20-8.954-20-20-20-20 8.954-20 20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .header-text {
            flex: 1;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .title-icon {
            color: var(--kuning-primary);
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .date-badge {
            background: rgba(255, 255, 158, 0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 158, 0.3);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Loading Spinner */
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 158, 0.3);
            border-top: 2px solid var(--kuning-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Statistics Grid - Made Smaller */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 4px 20px rgba(184, 20, 20, 0.1);
            border-left: 4px solid var(--merah-primary);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(184, 20, 20, 0.15);
        }

        .stats-card::after {
            content: '';
            position: absolute;
            top: -10px;
            right: -10px;
            width: 50px;
            height: 50px;
            background: var(--kuning-primary);
            opacity: 0.1;
            border-radius: 50%;
        }

        .stats-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--merah-primary);
            margin-bottom: 0.25rem;
        }

        .stats-label {
            color: var(--abu-gelap);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Main Content */
        .main-content {
            margin-bottom: 2rem;
        }

        /* Filter Section */
        .filter-section {
            padding: 1.5rem 2rem;
            background: var(--abu-terang);
            border-bottom: 1px solid #e2e8f0;
            border-radius: 20px 20px 0 0;
        }

        .filter-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--merah-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--kuning-primary);
            background: white;
            color: var(--merah-primary);
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            background: var(--kuning-primary);
            color: var(--merah-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 255, 158, 0.3);
        }

        .filter-btn.active {
            background: var(--merah-primary);
            color: white;
            border-color: var(--merah-primary);
        }

        .filter-btn.clear {
            background: var(--merah-secondary);
            border-color: var(--merah-secondary);
            color: white;
        }

        .filter-btn.clear:hover {
            background: #dc2626;
            border-color: #dc2626;
        }

        /* Main Card */
        .main-card {
            background: white;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header-custom {
            background: var(--merah-primary);
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .total-badge {
            background: rgba(255, 255, 158, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        /* Table Controls */
        .table-controls {
            padding: 1.5rem 2rem;
            background: var(--abu-terang);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .table-controls-left label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--merah-primary);
        }

        .table-controls-left select {
            border: 2px solid var(--kuning-primary);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            background: white;
            color: var(--merah-primary);
            font-weight: 600;
            margin: 0 0.5rem;
        }

        .search-input {
            border: 2px solid var(--kuning-primary);
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            background: white;
            color: var(--merah-primary);
            width: 300px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--merah-secondary);
            box-shadow: 0 0 0 3px rgba(234, 0, 0, 0.1);
        }

        .search-input::placeholder {
            color: #94a3b8;
        }

        /* Table Container */
        .table-container {
            padding: 2rem;
            overflow-x: auto;
        }

        /* Table Styling */
        .table-modern {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .table-modern thead th {
            background: var(--merah-primary);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1.25rem 1rem;
            border: none;
            text-align: left;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-modern tbody tr:hover {
            background: var(--kuning-secondary);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(184, 20, 20, 0.1);
        }

        .table-modern tbody tr:last-child {
            border-bottom: none;
        }

        .table-modern tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border: none;
        }

        .no-data {
            text-align: center;
            color: var(--abu-gelap);
            font-style: italic;
            padding: 3rem !important;
        }

        /* Table Content Styling */
        .row-number {
            background: var(--merah-secondary);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 40%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            transition: transform 0.3s ease;
        }

        .umkm-name {
            font-weight: 700;
            color: var(--merah-primary);
            font-size: 1rem;
        }

        .owner-info,
        .contact-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .owner-info {
            color: var(--abu-gelap);
            font-weight: 600;
        }

        .owner-info i {
            color: var(--merah-secondary);
        }

        .contact-info i {
            color: var(--kuning-primary);
            filter: brightness(0.7);
        }

        .contact-link {
            color: var(--merah-secondary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .contact-link:hover {
            color: var(--merah-primary);
        }

        /* Business Type Badges */
        .badge-business {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .badge-business::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
        }

        .badge-business:hover::before {
            left: 0;
        }

        .badge-dagang {
            background: var(--merah-primary);
            color: white;
        }

        .badge-dagang:hover {
            background: var(--merah-secondary);
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 6px 20px rgba(184, 20, 20, 0.4);
        }

        .badge-jasa {
            background: var(--merah-secondary);
            color: white;
        }

        .badge-jasa:hover {
            background: #dc2626;
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 6px 20px rgba(234, 0, 0, 0.4);
        }

        .badge-produksi {
            background: #c2410c;
            color: white;
        }

        .badge-produksi:hover {
            background: #ea580c;
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 6px 20px rgba(194, 65, 12, 0.4);
        }

        .badge-lainnya {
            background: var(--abu-gelap);
            color: white;
        }

        .badge-lainnya:hover {
            background: #475569;
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
        }

        /* Pulse animation for active filter */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(184, 20, 20, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(184, 20, 20, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(184, 20, 20, 0);
            }
        }

        .badge-business.filter-active {
            animation: pulse 2s infinite;
        }

        /* Image Preview */
        .image-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .image-preview:hover {
            transform: scale(1.1);
            border-color: var(--kuning-primary);
            box-shadow: 0 8px 25px rgba(255, 255, 158, 0.3);
        }

        /* Date Info */
        .date-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .date-text {
            font-size: 0.85rem;
            color: var(--abu-gelap);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .date-text strong {
            color: var(--merah-primary);
            font-weight: 700;
        }

        .time-text {
            font-size: 0.75rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Image Modal */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(5px);
        }

        .image-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
            border-radius: 12px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        }

        .image-modal-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10000;
        }

        .image-modal-close:hover {
            color: var(--kuning-primary);
            transform: scale(1.2);
        }

        /* DataTables Custom Styling */
        .dataTables_wrapper {
            padding: 0;
        }

        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 1.5rem;
        }

        .dataTables_length select {
            border: 2px solid var(--kuning-primary);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            background: white;
            color: var(--merah-primary);
            font-weight: 600;
        }

        .dataTables_filter input {
            border: 2px solid var(--kuning-primary) !important;
            border-radius: 25px !important;
            padding: 0.75rem 1.5rem !important;
            background: white !important;
            color: var(--merah-primary) !important;
            width: 300px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .dataTables_filter input:focus {
            outline: none !important;
            border-color: var(--merah-secondary) !important;
            box-shadow: 0 0 0 3px rgba(234, 0, 0, 0.1) !important;
        }

        .dataTables_info {
            color: var(--abu-gelap);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .dataTables_paginate .paginate_button {
            border: 2px solid var(--kuning-primary) !important;
            background: white !important;
            color: var(--merah-primary) !important;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
            font-weight: 600 !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: var(--kuning-primary) !important;
            color: var(--merah-primary) !important;
            transform: translateY(-2px) !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: var(--merah-primary) !important;
            color: white !important;
            border-color: var(--merah-primary) !important;
        }

        /* Filter Message */
        .filter-message {
            position: fixed;
            top: 100px;
            right: 20px;
            background: var(--merah-secondary);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(234, 0, 0, 0.3);
            z-index: 1000;
            animation: slideInRight 0.5s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: opacity 0.5s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Animation Classes */
        .fadeInUp {
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                padding: 0 1.5rem;
            }

            .search-input {
                width: 250px;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 2rem 0;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .stats-card {
                padding: 1rem;
            }

            .stats-number {
                font-size: 1.75rem;
            }

            .card-header-custom {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1.5rem;
            }

            .filter-buttons {
                justify-content: center;
            }

            .table-controls {
                flex-direction: column;
                gap: 1rem;
            }

            .search-input {
                width: 100%;
            }

            .table-container {
                padding: 1rem;
            }

            .image-preview {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 1rem;
            }

            .page-title {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-btn {
                font-size: 0.8rem;
                padding: 0.5rem 1rem;
            }

            .table-modern {
                font-size: 0.85rem;
            }

            .table-modern thead th,
            .table-modern tbody td {
                padding: 0.75rem 0.5rem;
            }

            .image-preview {
                width: 50px;
                height: 50px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="header-text">
                    <h1 class="page-title">
                        KEDATON - Data UMKM
                    </h1>
                    <p class="page-subtitle">
                        Kanal Ekonomi dan Data Usaha Terpeta Online - Bandar Lampung
                    </p>
                </div>
                <div class="header-actions">
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;"></div>
                    <div class="date-badge">
                        <i class="fas fa-calendar"></i>
                        {{ date('d M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stats-card" onclick="filterByType('Dagang')">
                <div class="stats-icon" style="background: var(--merah-primary);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stats-number">{{ $points->where('jenis_usaha', 'Dagang')->count() }}</div>
                <div class="stats-label">Dagang</div>
            </div>
            <div class="stats-card" onclick="filterByType('Jasa')">
                <div class="stats-icon" style="background: var(--merah-secondary);">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stats-number">{{ $points->where('jenis_usaha', 'Jasa')->count() }}</div>
                <div class="stats-label">Jasa</div>
            </div>
            <div class="stats-card" onclick="filterByType('Produksi')">
                <div class="stats-icon" style="background: #c2410c;">
                    <i class="fas fa-industry"></i>
                </div>
                <div class="stats-number">{{ $points->where('jenis_usaha', 'Produksi')->count() }}</div>
                <div class="stats-label">Produksi</div>
            </div>
            <div class="stats-card" onclick="filterByType('Lainnya')">
                <div class="stats-icon" style="background: var(--abu-gelap);">
                    <i class="fas fa-ellipsis-h"></i>
                </div>
                <div class="stats-number">{{ $points->where('jenis_usaha', 'Lainnya')->count() }}</div>
                <div class="stats-label">Lainnya</div>
            </div>
        </div>

        <!-- Main Data Table -->
        <div class="main-content">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-title">
                    <i class="fas fa-filter"></i>
                    Filter Jenis Usaha
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn clear" onclick="clearFilter()">
                        <i class="fas fa-times"></i>
                        Tampilkan Semua
                    </button>
                    <button class="filter-btn" data-filter="Dagang">
                        <i class="fas fa-shopping-cart"></i>
                        Dagang
                    </button>
                    <button class="filter-btn" data-filter="Jasa">
                        <i class="fas fa-user-tie"></i>
                        Jasa
                    </button>
                    <button class="filter-btn" data-filter="Produksi">
                        <i class="fas fa-industry"></i>
                        Produksi
                    </button>
                    <button class="filter-btn" data-filter="Lainnya">
                        <i class="fas fa-ellipsis-h"></i>
                        Lainnya
                    </button>
                </div>
            </div>

            <div class="main-card">
                <div class="card-header-custom">
                    <h4 class="card-title">
                        Tabel Data UMKM Bandar Lampung
                    </h4>
                    <div class="total-badge">
                        Total: {{ $points->count() }} UMKM
                    </div>
                </div>

                <div class="table-container">
                    <table class="table table-modern" id="pointstable">
                        <thead>
                            <tr>
                                <th class="text-center align-middle" width="60">No</th>
                                <th class="text-center align-middle">Nama UMKM</th>
                                <th class="text-center align-middle">Pemilik</th>
                                <th class="text-center align-middle">Kontak</th>
                                <th class="text-center align-middle" width="130">Jenis Usaha</th>
                                <th class="text-center align-middle" width="100">Foto</th>
                                <th class="text-center align-middle" width="120">Dibuat</th>
                                <th class="text-center align-middle" width="120">Diperbarui</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($points as $index => $p)
                                <tr>
                                    <td>
                                        <div class="row-number">{{ $index + 1 }}</div>
                                    </td>
                                    <td>
                                        <div class="umkm-name">{{ $p->nama_umkm }}</div>
                                    </td>
                                    <td>
                                        <div class="owner-info">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $p->pemilik }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            <i class="fas fa-phone"></i>
                                            <a href="tel:{{ $p->telepon }}" class="contact-link">
                                                {{ $p->telepon }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-business badge-{{ strtolower($p->jenis_usaha) }}"
                                            onclick="filterByType('{{ $p->jenis_usaha }}')"
                                            title="Klik untuk filter {{ $p->jenis_usaha }}">
                                            {{ $p->jenis_usaha }}
                                        </span>
                                    </td>
                                    <td>
                                        <img src="{{ asset('storage/images/' . $p->image) }}" alt="{{ $p->nama_umkm }}"
                                            class="image-preview"
                                            onclick="openImageModal('{{ asset('storage/images/' . $p->image) }}', '{{ $p->nama_umkm }}')"
                                            title="Klik untuk memperbesar">
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <div class="date-text">
                                                <i class="fas fa-calendar" style="font-size: 14px;"></i>
                                                <strong>{{ $p->created_at->format('d M Y') }}</strong>
                                            </div>
                                            <div class="time-text">
                                                <i class="fas fa-clock" style="font-size: 12px;"></i>
                                                <small>{{ $p->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <div class="date-text">
                                                <i class="fas fa-calendar" style="font-size: 14px;"></i>
                                                <strong>{{ $p->updated_at->format('d M Y') }}</strong>
                                            </div>
                                            <div class="time-text">
                                                <i class="fas fa-clock" style="font-size: 12px;"></i>
                                                <small>{{ $p->updated_at->format('H:i') }} WIB</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script>
        let currentFilter = '';

        $(document).ready(function() {
            // Show loading spinner
            $('#loadingSpinner').show();

            // Initialize DataTable
            let table = $('#pointstable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data UMKM tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_ (_TOTAL_ total UMKM)",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari UMKM:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 10,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Semua"]
                ],
                "order": [
                    [0, "asc"]
                ],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [5]
                    },
                    {
                        "searchable": false,
                        "targets": [0, 5]
                    }
                ],
                "drawCallback": function(settings) {
                    // Add animation to rows
                    $('#pointstable tbody tr').each(function(index) {
                        $(this).css('animation-delay', (index * 0.05) + 's');
                        $(this).addClass('fadeInUp');
                    });

                    // Update badge states based on current filter
                    updateBadgeStates();
                },
                "initComplete": function(settings, json) {
                    $('#loadingSpinner').hide();
                    $('.dataTables_filter input').attr('placeholder',
                        'Cari nama UMKM, pemilik, atau nomor telepon...');
                }
            });

            // Filter button functionality
            $('.filter-btn[data-filter]').on('click', function() {
                const filterValue = $(this).data('filter');
                currentFilter = filterValue;

                // Update button states
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');

                // Apply filter
                table.column(4).search(filterValue).draw();

                // Show filter message
                showFilterMessage(`Filter aktif: ${filterValue}`);
            });

            // Enhanced hover effects
            $('#pointstable tbody').on('mouseenter', 'tr', function() {
                $(this).find('.image-preview').css('transform', 'scale(1.1)');
                $(this).find('.row-number').css('transform', 'scale(1.1)');
            }).on('mouseleave', 'tr', function() {
                $(this).find('.image-preview').css('transform', 'scale(1)');
                $(this).find('.row-number').css('transform', 'scale(1)');
            });
        });

        // Update badge states based on current filter
        function updateBadgeStates() {
            $('.badge-business').removeClass('filter-active');

            if (currentFilter) {
                $(`.badge-business:contains("${currentFilter}")`).addClass('filter-active');
            }
        }

        // Filter functions
        function filterByType(type) {
            const table = $('#pointstable').DataTable();
            currentFilter = type;

            // Update button states
            $('.filter-btn').removeClass('active');
            $(`.filter-btn[data-filter="${type}"]`).addClass('active');

            // Apply filter
            table.column(4).search(type).draw();

            // Show filter message
            showFilterMessage(`Filter aktif: ${type}`);
        }

        function clearFilter() {
            const table = $('#pointstable').DataTable();
            currentFilter = '';

            // Clear button states
            $('.filter-btn').removeClass('active');

            // Clear filter
            table.column(4).search('').draw();

            // Show clear message
            showFilterMessage('Filter dibersihkan - Menampilkan semua data');
        }

        // Filter message function
        function showFilterMessage(message) {
            // Remove existing message
            $('.filter-message').remove();

            // Create and show new message
            const messageEl = $(`
                <div class="filter-message">
                    <i class="fas fa-info-circle"></i>${message}
                </div>
            `);

            $('body').append(messageEl);

            // Auto remove after 3 seconds
            setTimeout(() => {
                messageEl.fadeOut(500, () => messageEl.remove());
            }, 3000);
        }

        // Image modal functions
        function openImageModal(imageSrc, altText) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');

            modal.style.display = 'block';
            modalImg.src = imageSrc;
            modalImg.alt = altText;

            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection
