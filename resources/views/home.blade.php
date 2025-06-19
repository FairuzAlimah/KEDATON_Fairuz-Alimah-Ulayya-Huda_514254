@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background-color: #b81414;
        }

        #map {
            width: 100%;
            height: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }

        #map:hover {
            transform: scale(1.02);
        }

        .statistik-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid #FFFF9E;
            border-radius: 10px;
        }

        .statistik-card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.4);
            cursor: pointer;
        }

        canvas {
            max-height: 300px;
        }

        .btn-custom {
            border-radius: 20px;
            padding: 10px 25px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-custom:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .judul-banner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #FFFF9E;
            font-family: 'Poppins', sans-serif;
            font-weight: 900;
            font-size: 36px;
            line-height: 1.1;
            transition: color 0.3s ease;
            opacity: 0;
            animation: fadeSlideUp 1s ease-out forwards;
        }

        .judul-banner:hover {
            color: #B22222;
        }

        .judul-utama {
            font-size: 42px;
            display: block;
            margin-bottom: 10px;
        }

        .judul-sub {
            font-size: 24px;
            display: block;
            white-space: nowrap;
        }

        /* Animasi */
        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translate(-50%, -40%);
            }

            100% {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }
    </style>
@endsection

@section('content')
    <div class="container px-4">

        <!-- Header -->
        <div class="mb-4 position-relative" style="border-radius: 10px; overflow: hidden;">
            <div
                style="background-image: url('{{ asset('storage/images/tapis.jpg') }}');
                background-size: cover;
                background-position: center;
                height: 250px;
                filter: brightness(60%);">
            </div>
            <div class="judul-banner">
                <span class="judul-utama">KEDATON</span>
                <span class="judul-sub">Kanal Ekonomi dan Data Usaha Terpeta Online</span>
            </div>
        </div>

        <!-- Navigasi -->
        <div class="mb-4 text-center">
            <a href="{{ route('home') }}" class="btn btn-custom m-2"
                style="background-color:#FFFF9E; color:#B22222;">Halaman
                Utama</a>
            <a href="{{ route('map') }}" class="btn btn-custom m-2" style="background-color:#ffffdf; color: #ea0000;">Peta
                Interaktif</a>
            <a href="{{ route('table') }}" class="btn btn-custom m-2"
                style="background-color:#FFFF9E; color: #B22222;">Data Tabel</a>
            <a href="{{ route('kedaton') }}" class="btn btn-custom m-2"
                style="background-color:#ffffdf; color: #ea0000;">Tentang Kami</a>
        </div>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm text-center statistik-card">
                    <div class="card-body">
                        <h5 style="color:#B22222;">Total UMKM</h5>
                        <h2 style="color:#ecec53;">{{ $total_umkm }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card shadow-sm text-center statistik-card">
                    <div class="card-body">
                        <h5 style="color:#B22222;">Kategori UMKM</h5>
                        <h2 style="color:#ecec53;">{{ $kategori_umkm }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="card shadow-sm mb-6" style="border-top: 4px solid #FFFF9E; border-radius:10px;">
            <div class="card-header" style="background-color: #FFFF9E; color: #B22222; font-weight: bold;">Distribusi UMKM
                per Kategori</div>
            <div class="card-body text-center">
                <div style="max-width: 500px; margin: 0 auto;"><canvas id="umkmChart"></canvas></div>
            </div>
        </div>

        <!-- Peta -->
        <br>
        <div class="card shadow-sm mb-6" style="border-top: 4px solid #ffffdf; border-radius:10px;">
            <div class="card-header" style="background-color: #ffffdf; color: #ea0000; font-weight: bold;">Peta Persebaran
                UMKM</div>
            <div class="card-body position-relative">
                <div id="map"></div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Inisialisasi Peta
        const map = L.map('map').setView([-5.437, 105.266], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Layer Geoserver
        const geoserverLayer = L.tileLayer.wms('http://localhost:8080/geoserver/responsi_pgwl/wms', {
            layers: 'responsi_pgwl:area_balam',
            format: 'image/png',
            transparent: true,
            zIndex: 100
        }).addTo(map);

        const pointLayer = L.geoJson(null, {
            pointToLayer: function(feature, latlng) {
                const jenis = (feature.properties.jenis_usaha || '').toLowerCase();

                let iconPath = '{{ asset('storage/icons/lainnya.png') }}'; // default ikon

                if (jenis.includes('dagang')) {
                    iconPath = '{{ asset('storage/icons/dagang.png') }}';
                } else if (jenis.includes('produksi')) {
                    iconPath = '{{ asset('storage/icons/produksi.png') }}';
                } else if (jenis.includes('jasa')) {
                    iconPath = '{{ asset('storage/icons/jasa.png') }}';
                } else if (jenis.includes('lainnya')) {
                    iconPath = '{{ asset('storage/icons/lainnya.png') }}';
                }

                const customIcon = L.icon({
                    iconUrl: iconPath,
                    iconSize: [30, 40],
                    iconAnchor: [15, 40],
                    popupAnchor: [0, -40]
                });

                return L.marker(latlng, {
                    icon: customIcon
                });
            },
            onEachFeature: function(feature, layer) {
                const props = feature.properties;
                layer.bindTooltip(props.nama_umkm, {
                    permanent: false,
                    direction: 'top'
                });
            }
        }).addTo(map);

        // Load data point dari API
        $.getJSON("{{ route('api.points') }}", function(data) {
            pointLayer.addData(data);
        });

        $.getJSON("{{ route('api.points') }}", function(data) {
            pointLayer.addData(data);
        });

        // Chart
        const ctx = document.getElementById('umkmChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Jumlah UMKM',
                    data: {!! json_encode($data) !!},
                    backgroundColor: [
                        '#FFFF9E',
                        '#FFFF9E',
                        '#FFFF9E',
                        '#FFFF9E',
                        '#FFFF9E',
                        '#FFFF9E'
                    ],
                    borderColor: [
                        '#B22222',
                        '#B22222',
                        '#B22222',
                        '#B22222',
                        '#B22222',
                        '#B22222'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
