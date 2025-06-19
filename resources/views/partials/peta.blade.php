<!-- Peta -->
<div id="map" style="height: 500px; border-radius: 10px;"></div>

<!-- Tambahkan Leaflet CSS (jika belum di layout) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Tambahkan Leaflet JS (jika belum di layout) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var map = L.map('map').setView([-5.3971, 105.2668], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Contoh marker UMKM (bisa kamu ganti dengan data dari database)
        L.marker([-5.3971, 105.2668]).addTo(map)
            .bindPopup('Contoh UMKM 1').openPopup();

        L.marker([-5.3920, 105.2730]).addTo(map)
            .bindPopup('Contoh UMKM 2');
    });
</script>
