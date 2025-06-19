@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }

    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Edit Point -->
    <div class="modal fade" id="editpointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Titik UMKM</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('points.update', $id) }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="nama_umkm" class="form-label">Nama UMKM</label>
                            <input type="text" class="form-control" id="nama_umkm" name="nama_umkm">
                        </div>

                        <div class="mb-3">
                            <label for="pemilik" class="form-label">Pemilik UMKM</label>
                            <textarea class="form-control" id="pemilik" name="pemilik" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telp/HP</label>
                            <textarea class="form-control" id="telepon" name="telepon" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Jenis Usaha</label>
                            <select class="form-select" name="jenis_usaha" id="jenis_usaha" required>
                                <option value="Dagang">Dagang</option>
                                <option value="Jasa">Jasa</option>
                                <option value="Produksi">Produksi</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="geom_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3"></textarea>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
        // Normalisasi tulisan (capitalize)
        function capitalizeWords(str) {
            if (!str) return "";
            return str.toLowerCase().replace(/(^|\s)\S/g, function(t) {
                return t.toUpperCase()
            });
        }

        // Fungsi untuk menentukan icon berdasarkan jenis usaha
        function getIcon(jenis_usaha) {
            let jenis = (jenis_usaha || '').toLowerCase();
            let iconUrl = '';

            if (jenis === 'dagang') {
                iconUrl = '/storage/icons/dagang.png';
            } else if (jenis === 'jasa') {
                iconUrl = '/storage/icons/jasa.png';
            } else if (jenis === 'produksi') {
                iconUrl = '/storage/icons/produksi.png';
            } else {
                iconUrl = '/storage/icons/lainnya.png';
            }


            return L.icon({
                iconUrl: iconUrl,
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });
        }

        var map = L.map('map').setView([-7.0518588624223115, 110.44083929151641], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });
        map.addControl(drawControl);

        map.on('draw:edited', function(e) {
            var layers = e.layers;
            layers.eachLayer(function(layer) {
                var drawnJSONObject = layer.toGeoJSON();
                var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
                var properties = drawnJSONObject.properties;

                $('#nama_umkm').val(properties.nama_umkm);
                $('#pemilik').val(properties.pemilik);
                $('#telepon').val(properties.telepon);
                $('#jenis_usaha').val(capitalizeWords(properties.jenis_usaha));
                $('#geom_point').val(objectGeometry);
                $('#preview-image-point').attr('src', "{{ asset('storage/images') }}/" + properties.image);

                $('#editpointModal').modal('show');
            });
        });

        var point = L.geoJson(null, {
            pointToLayer: function(feature, latlng) {
                return L.marker(latlng, {
                    icon: getIcon(feature.properties.jenis_usaha)
                });
            },
            onEachFeature: function(feature, layer) {
                drawnItems.addLayer(layer);
                var properties = feature.properties;
                var objectGeometry = Terraformer.geojsonToWKT(feature.geometry);

                layer.on('click', function() {
                    $('#nama_umkm').val(properties.nama_umkm);
                    $('#pemilik').val(properties.pemilik);
                    $('#telepon').val(properties.telepon);
                    $('#jenis_usaha').val(capitalizeWords(properties.jenis_usaha));
                    $('#geom_point').val(objectGeometry);
                    $('#preview-image-point').attr('src', "{{ asset('storage/images') }}/" + properties
                        .image);

                    $('#editpointModal').modal('show');
                });
            }
        });

        $.getJSON("{{ route('api.point', $id) }}", function(data) {
            point.addData(data);
            map.addLayer(point);
            map.fitBounds(point.getBounds(), {
                padding: [100, 100]
            });
        });
    </script>
@endsection
