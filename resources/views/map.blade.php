@extends('layout.template')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/2.9.7/leaflet-search.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    #map {
        width: 100%;
        height: calc(100vh - 56px);
    }

    /* Tombol Leaflet Search agar seragam dengan tombol lainnya */
    .leaflet-control-search .search-button {
        width: 30px;
        height: 30px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        outline: none;
    }

    /* Ikon Font Awesome di dalam tombol pencarian */
    .leaflet-control-search .search-button i {
        font-size: 14px;
        color: #333;
    }

    /* Hilangkan garis biru saat tombol aktif/fokus */
    .leaflet-control-search .search-button:focus,
    .leaflet-control-search .search-button:active {
        outline: none;
        box-shadow: none;
    }

    /* Ubah warna ikon saat hover */
    .leaflet-control-search .search-button:hover i {
        color: #B22222;
    }

    /* Style untuk search control yang disabled */
    .leaflet-control-search.search-disabled .search-button {
        background: #f5f5f5;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .leaflet-control-search.search-disabled .search-button i {
        color: #999;
    }

    .leaflet-control-search.search-disabled .search-button:hover i {
        color: #999;
    }

    /* Style untuk input search yang disabled */
    .leaflet-control-search.search-disabled .search-input {
        background: #f5f5f5 !important;
        color: #999 !important;
        cursor: not-allowed !important;
    }

    /* Notification untuk search disabled */
    .search-disabled-notification {
        position: absolute;
        top: 70px;
        left: 50%;
        transform: translateX(-50%);
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        display: none;
        max-width: 400px;
        text-align: center;
    }

    /* Legend style tetap seperti desain awal */
    .legend-wrapper {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }

    .legend-toggle {
        background: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 6px 12px;
        cursor: pointer;
        font-size: 14px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    .legend-panel {
        position: absolute;
        bottom: 60px;
        right: 0;
        width: 250px;
        max-height: 300px;
        background: white;
        border-radius: 8px;
        padding: 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        display: none;
        overflow: hidden;
    }

    .legend-header {
        position: sticky;
        top: 0;
        z-index: 2;
        background: white;
        padding: 8px 10px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .legend-header h5 {
        margin: 0;
        font-size: 19px;
    }

    .close-btn {
        background: transparent;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

    .legend-content {
        max-height: 250px;
        overflow-y: auto;
        padding: 8px 10px;
    }

    .legend-panel img {
        max-width: 100%;
        border-radius: 4px;
        margin-top: 2px;
    }

    /* Style untuk section legend yang dapat disembunyikan */
    .legend-section {
        margin-bottom: 15px;
        transition: opacity 0.3s ease;
    }

    .legend-section:last-child {
        margin-bottom: 5px;
    }

    .legend-section hr {
        margin: 10px 0;
    }

    /* Style untuk legend items */
    .legend-item {
        display: flex;
        align-items: center;
        margin: 5px 0;
    }

    .legend-item img {
        margin-right: 8px;
    }

    /* Enhanced popup styling untuk mencegah cutoff */
    .leaflet-popup {
        margin-bottom: 20px;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .leaflet-popup-content {
        margin: 0;
        width: auto !important;
    }

    .leaflet-popup-tip {
        background: white;
    }

    /* Custom popup card styling */
    .popup-card {
        width: 220px;
        font-size: 12px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .popup-image {
        height: 100px;
        object-fit: cover;
        width: 100%;
        border-radius: 4px 4px 0 0;
    }

    .popup-body {
        padding: 12px;
        background: white;
    }

    .popup-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1f2937;
    }

    .popup-text {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .popup-actions {
        display: flex;
        gap: 6px;
    }

    .popup-btn {
        flex: 1;
        padding: 6px 8px;
        font-size: 10px;
        font-weight: 500;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
    }

    .popup-btn-edit {
        background: #f59e0b;
        color: white;
    }

    .popup-btn-edit:hover {
        background: #d97706;
        color: white;
    }

    .popup-btn-delete {
        background: #ef4444;
        color: white;
    }

    .popup-btn-delete:hover {
        background: #dc2626;
    }
</style>
@endsection

@section('content')
<div id="map"></div>

<!-- Search Disabled Notification -->
<div id="searchDisabledNotification" class="search-disabled-notification">
    Pencarian dinonaktifkan - aktifkan layer "Titik UMKM" untuk mencari data titik UMKM
</div>

<!-- Legend -->
<div class="legend-wrapper">
    <div class="legend-toggle" onclick="toggleLegend()">Legenda</div>
    <div class="legend-panel" id="legendPanel">
        <div class="legend-header">
            <h5>Legenda</h5>
            <button class="close-btn" onclick="toggleLegend()">&times;</button>
        </div>
        <div class="legend-content">
            <!-- Legend section untuk Titik UMKM -->
            <div id="legend-umkm" class="legend-section">
                <b>Jenis UMKM:</b><br>
                <div class="legend-item"><img src="/storage/icons/dagang.png" width="30"> Dagang</div>
                <div class="legend-item"><img src="/storage/icons/jasa.png" width="30"> Jasa</div>
                <div class="legend-item"><img src="/storage/icons/produksi.png" width="30"> Produksi</div>
                <div class="legend-item"><img src="/storage/icons/lainnya.png" width="30"> Lainnya</div>
            </div>

            <!-- Legend section untuk Jaringan Jalan -->
            <div id="legend-jalan" class="legend-section">
                <hr>
                <b>Jaringan Jalan:</b><br>
                <div class="legend-item">
                    <img src="http://localhost:8080/geoserver/responsi_pgwl/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=30&HEIGHT=30&LAYER=responsi_pgwl:jalan">
                </div>
            </div>

            <!-- Legend section untuk Batas Administrasi -->
            <div id="legend-admin" class="legend-section">
                <hr>
                <b>Batas Administrasi:</b><br>
                <div class="legend-item">
                    <img src="http://localhost:8080/geoserver/responsi_pgwl/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=30&HEIGHT=30&LAYER=responsi_pgwl:area_balam">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="CreatePointModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('points.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Titik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label>Nama UMKM</label><input type="text" class="form-control"
                            name="nama_umkm" required></div>
                    <div class="mb-3"><label>Pemilik UMKM</label>
                        <textarea class="form-control" name="pemilik" required></textarea>
                    </div>
                    <div class="mb-3"><label>Telp/HP</label>
                        <textarea class="form-control" name="telepon" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Usaha</label>
                        <select class="form-select" name="jenis_usaha" required>
                            <option value="">-- Pilih Jenis Usaha --</option>
                            <option value="Dagang">Dagang</option>
                            <option value="Jasa">Jasa</option>
                            <option value="Produksi">Produksi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3"><label>Geometri</label>
                        <textarea class="form-control" id="geom_point" name="geom_point" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="image_point" name="image"
                            onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                        <img src="" alt="" id="preview-image-point" class="img-thumbnail"
                            width="200">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const csrfToken = "{{ csrf_token() }}";
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/@terraformer/wkt"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/2.9.7/leaflet-search.min.js"></script>

<script>
    const map = L.map('map').setView([-5.437, 105.266], 11);
    const defaultLatLng = [-5.437, 105.266]; // koordinat pusat
    const defaultZoom = 11; // level zoom

    // Variable untuk tracking search control dan status
    let searchControl = null;
    let isSearchEnabled = true;

    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.getElementById('resetViewBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                map.setView(defaultLatLng, defaultZoom);
            });
        }
    });

    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const esri = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

    const googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google'
    });

    const jalanLayer = L.tileLayer.wms('http://localhost:8080/geoserver/responsi_pgwl/wms', {
        layers: 'responsi_pgwl:jalan',
        format: 'image/png',
        transparent: true
    }).addTo(map);

    const geoserverLayer = L.tileLayer.wms('http://localhost:8080/geoserver/responsi_pgwl/wms', {
        layers: 'responsi_pgwl:area_balam',
        format: 'image/png',
        transparent: true
    }).addTo(map);

    function getIcon(jenis) {
        let fileName = jenis ? jenis.toLowerCase() + '.png' : 'lainnya.png';
        return L.icon({
            iconUrl: '/storage/icons/' + fileName,
            iconSize: [35, 35],
            iconAnchor: [17, 35],
            popupAnchor: [0, -35]
        });
    }

    const pointLayer = L.geoJson(null, {
        pointToLayer: function(feature, latlng) {
            return L.marker(latlng, {
                icon: getIcon(feature.properties.jenis_usaha)
            });
        },
        onEachFeature: function(feature, layer) {
            const props = feature.properties;

            const popup = `
                <div class="popup-card">
                    <img src="/storage/images/${props.image}" class="popup-image" alt="Foto UMKM">
                    <div class="popup-body">
                        <h6 class="popup-title">${props.nama_umkm}</h6>
                        <div class="popup-text">
                            <strong>Pemilik:</strong> ${props.pemilik}<br>
                            <strong>Jenis Usaha:</strong> ${props.jenis_usaha}<br>
                            <strong>Telepon:</strong> ${props.telepon}<br>
                            <strong>Ditambahkan Oleh:</strong> ${props.user_created}
                        </div>
                        <div class="popup-actions">
                            <a href="/points/${props.id}/edit" class="popup-btn popup-btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="/points/${props.id}" method="POST" style="flex: 1;" onsubmit="return confirm('Yakin ingin menghapus?')">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="popup-btn popup-btn-delete" style="width: 100%;">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            // Enhanced popup options untuk mencegah cutoff
            layer.bindPopup(popup, {
                maxWidth: 280,
                autoPan: true,
                autoPanPadding: [20, 20],
                keepInView: true,
                closeButton: true,
                autoClose: false,
                className: 'custom-popup'
            }).bindTooltip(props.nama_umkm);
        }
    }).addTo(map);

    // Tambahkan tombol kembali ke view awal
    const resetButton = L.control({
        position: 'topleft'
    });

    resetButton.onAdd = function() {
        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
        div.innerHTML =
            '<button id="resetViewBtn" title="Kembali ke Tampilan Awal" ' +
            'style="background-color: white; color: black; height: 30px; width: 32px; ' +
            'border: 1px solid #ccc; border-radius: 4px; font-size: 16px; ' +
            'cursor: pointer; box-shadow: 0 1px 5px rgba(0,0,0,0.65); display: flex; align-items: center; justify-content: center;">' +
            '<i class="fa fa-home"  style="font-size: 12px;"></i>' +
            '</button>';

        L.DomEvent.disableClickPropagation(div);
        return div;
    };

    resetButton.addTo(map);

    let highlightIcon = L.icon({
        iconUrl: '/storage/icons/location.png', // ikon saat disorot hasil pencarian
        iconSize: [35, 35],
        iconAnchor: [17, 35],
        popupAnchor: [0, -35]
    });

    let lastHighlighted = null;

    // Fungsi untuk mengaktifkan/menonaktifkan search
    function toggleSearchControl(enabled) {
        if (!searchControl) return;

        const searchContainer = searchControl.getContainer();
        const searchInput = searchContainer.querySelector('.search-input');
        const searchButton = searchContainer.querySelector('.search-button');
        const notification = document.getElementById('searchDisabledNotification');

        if (enabled) {
            // Aktifkan search
            searchContainer.classList.remove('search-disabled');
            if (searchInput) {
                searchInput.disabled = false;
                searchInput.placeholder = 'Cari UMKM...';
            }
            if (searchButton) {
                searchButton.disabled = false;
            }
            notification.style.display = 'none';
            isSearchEnabled = true;
        } else {
            // Nonaktifkan search
            searchContainer.classList.add('search-disabled');
            if (searchInput) {
                searchInput.disabled = true;
                searchInput.placeholder = 'Aktifkan layer titik untuk mencari...';
                searchInput.value = '';
            }
            if (searchButton) {
                searchButton.disabled = true;
            }
            notification.style.display = 'block';
            isSearchEnabled = false;

            // Reset highlight jika ada
            if (lastHighlighted) {
                const jenisUsaha = lastHighlighted.feature.properties.jenis_usaha;
                lastHighlighted.setIcon(getIcon(jenisUsaha));
                lastHighlighted = null;
            }
        }
    }

    $.getJSON("{{ route('api.points') }}", function(data) {
        pointLayer.addData(data);

        searchControl = new L.Control.Search({
            layer: pointLayer,
            propertyName: 'nama_umkm',
            marker: false,
            textPlaceholder: 'Cari UMKM...',
            moveToLocation: function(latlng, title, map) {
                // Enhanced moveToLocation untuk mencegah popup cutoff
                const targetZoom = 14;

                // Hitung offset untuk memastikan popup tidak terpotong
                const mapSize = map.getSize();
                const popupOffset = L.point(0, -50); // Offset ke atas untuk popup

                // Gunakan flyTo dengan offset
                map.flyTo(latlng, targetZoom, {
                    animate: true,
                    duration: 0.8,
                    easeLinearity: 0.25
                });

                // Delay untuk memastikan animasi selesai sebelum membuka popup
                setTimeout(() => {
                    // Pastikan popup terbuka dengan posisi yang tepat
                    const targetLayer = pointLayer.getLayers().find(layer =>
                        layer.getLatLng().equals(latlng)
                    );

                    if (targetLayer && targetLayer.getPopup()) {
                        targetLayer.openPopup();

                        // Adjust map view jika popup masih terpotong
                        setTimeout(() => {
                            const popup = targetLayer.getPopup();
                            if (popup && popup.isOpen()) {
                                const popupPane = map.getPane('popupPane');
                                const popupEl = popupPane.querySelector('.leaflet-popup');

                                if (popupEl) {
                                    const popupRect = popupEl.getBoundingClientRect();
                                    const mapRect = map.getContainer().getBoundingClientRect();

                                    // Cek apakah popup terpotong di bagian bawah
                                    if (popupRect.bottom > mapRect.bottom - 20) {
                                        const currentCenter = map.getCenter();
                                        const offsetLat = (popupRect.bottom - mapRect.bottom + 40) / mapRect.height *
                                                         (map.getBounds().getNorth() - map.getBounds().getSouth());
                                        map.panTo([currentCenter.lat - offsetLat, currentCenter.lng], {
                                            animate: true,
                                            duration: 0.3
                                        });
                                    }

                                    // Cek apakah popup terpotong di bagian kanan
                                    if (popupRect.right > mapRect.right - 20) {
                                        const currentCenter = map.getCenter();
                                        const offsetLng = (popupRect.right - mapRect.right + 40) / mapRect.width *
                                                         (map.getBounds().getEast() - map.getBounds().getWest());
                                        map.panTo([currentCenter.lat, currentCenter.lng - offsetLng], {
                                            animate: true,
                                            duration: 0.3
                                        });
                                    }
                                }
                            }
                        }, 100);
                    }
                }, 900);
            }
        });

        // Override search function untuk cek status enabled
        const originalSearch = searchControl._handleSubmit;
        searchControl._handleSubmit = function(e) {
            if (!isSearchEnabled) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            return originalSearch.call(this, e);
        };

        searchControl.on('search:locationfound', function(e) {
            if (!isSearchEnabled) return;

            // Set ikon highlight untuk layer yang ditemukan
            e.layer.setIcon(highlightIcon);
            lastHighlighted = e.layer; // Simpan layer yang disorot
        });

        searchControl.on('search:collapsed', function(e) {
            // Kembalikan ikon ke default menggunakan getIcon
            pointLayer.eachLayer(function(layer) {
                const jenisUsaha = layer.feature.properties.jenis_usaha;
                layer.setIcon(getIcon(jenisUsaha));
            });
            lastHighlighted = null;
        });

        map.addControl(searchControl);
        searchControl._button.innerHTML = '<i class="fas fa-search"></i>';

        // Set initial search state
        toggleSearchControl(map.hasLayer(pointLayer));
    });

    const drawnItems = new L.FeatureGroup().addTo(map);
    map.addControl(new L.Control.Draw({
        draw: {
            polygon: false,
            polyline: false,
            rectangle: false,
            circle: false,
            circlemarker: false
        },
        edit: false
    }));

    map.on('draw:created', function(e) {
        const layer = e.layer;
        const geojson = layer.toGeoJSON();
        const wkt = Terraformer.geojsonToWKT(geojson.geometry);
        $('#geom_point').val(wkt);
        $('#CreatePointModal').modal('show');
        drawnItems.addLayer(layer);
    });

    // Definisikan baseMaps dan overlayMaps
    const baseMaps = {
        "OpenStreetMap": osm,
        "Esri Satellite": esri,
        "Google Hybrid": googleHybrid
    };

    const overlayMaps = {
        "Batas Administrasi": geoserverLayer,
        "Jaringan Jalan": jalanLayer,
        "Titik UMKM": pointLayer
    };

    // Buat layer control
    const layerControl = L.control.layers(baseMaps, overlayMaps, {
        collapsed: true
    }).addTo(map);

    // Fungsi untuk mengupdate legend berdasarkan layer yang aktif
    function updateLegend() {
        const legendUmkm = document.getElementById('legend-umkm');
        const legendJalan = document.getElementById('legend-jalan');
        const legendAdmin = document.getElementById('legend-admin');

        // Cek apakah layer ada di map (berarti aktif)
        const umkmActive = map.hasLayer(pointLayer);
        const jalanActive = map.hasLayer(jalanLayer);
        const adminActive = map.hasLayer(geoserverLayer);

        // Update search control berdasarkan status layer UMKM
        toggleSearchControl(umkmActive);

        // Tampilkan/sembunyikan legend sections
        legendUmkm.style.display = umkmActive ? 'block' : 'none';
        legendJalan.style.display = jalanActive ? 'block' : 'none';
        legendAdmin.style.display = adminActive ? 'block' : 'none';

        // Handle hr (garis pemisah) visibility
        const allSections = [legendUmkm, legendJalan, legendAdmin];
        const visibleSections = allSections.filter(section => section.style.display !== 'none');

        // Sembunyikan semua hr terlebih dahulu
        document.querySelectorAll('.legend-section hr').forEach(hr => {
            hr.style.display = 'none';
        });

        // Tampilkan hr hanya di antara section yang terlihat (kecuali yang pertama)
        for (let i = 1; i < visibleSections.length; i++) {
            const hr = visibleSections[i].querySelector('hr');
            if (hr) {
                hr.style.display = 'block';
            }
        }
    }

    // Event listener untuk layer add/remove
    map.on('layeradd', function(e) {
        updateLegend();
    });

    map.on('layerremove', function(e) {
        updateLegend();
    });

    // Update legend saat pertama kali load
    updateLegend();

    function toggleLegend() {
        const panel = document.getElementById('legendPanel');
        panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
    }

    // Hide notification when clicking elsewhere
    document.addEventListener('click', function(e) {
        const notification = document.getElementById('searchDisabledNotification');
        const searchControl = document.querySelector('.leaflet-control-search');

        if (notification.style.display === 'block' &&
            !notification.contains(e.target) &&
            !searchControl.contains(e.target)) {
            setTimeout(() => {
                if (!isSearchEnabled) {
                    notification.style.display = 'block';
                }
            }, 3000);
        }
    });
</script>
@endsection
