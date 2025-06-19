<x-app-layout>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="display-4">Dashboard UMKM Bandar Lampung</h1>
        <p class="lead">Pusat data dan perkembangan UMKM di Bandar Lampung</p>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">Total UMKM</h5>
                    <h2 class="text-primary">{{ $total_umkm }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">UMKM Aktif</h5>
                    <h2 class="text-success">{{ $umkm_aktif }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">Kategori</h5>
                    <h2 class="text-warning">{{ $kategori_umkm }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            Distribusi UMKM per Kategori
        </div>
        <div class="card-body">
            <canvas id="umkmChart"></canvas>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center mt-4">
        <a href="{{ route('map') }}" class="btn btn-outline-primary btn-lg">
            🌐 Lihat Peta UMKM
        </a>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('umkmChart').getContext('2d');
    const umkmChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chart_data['labels']) !!},
            datasets: [{
                label: 'Jumlah UMKM',
                data: {!! json_encode($chart_data['data']) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</x-app-layout>
