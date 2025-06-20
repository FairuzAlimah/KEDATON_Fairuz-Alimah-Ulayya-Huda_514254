@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background-color: #ffffdf;
            font-family: 'Poppins', sans-serif;
            transition: background-color 0.3s ease;
        }

        h1 {
            font-weight: 700;
            font-size: 3rem;
            /* atau sesuaikan, misal 32px */
            transition: color 0.3s ease;
        }

        h4 {
            font-weight: 700;
            font-size: 1.2rem;
            /* sekitar 20px */
            transition: color 0.3s ease;
        }
        .judul-app small.text {
    display: block;
    margin-top: 0.25rem; /* kecilkan jarak */
    line-height: 1.2; /* rapatkan teks */
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
                transform: translateY(-20px);
            }

            60% {
                transform: translateY(-10px);
            }
        }

        .logo-bounce {
            animation: bounce 3s infinite;
        }

        .judul-app {
            color: #B22222;
            text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .judul-app i {
            color: #FF5733;
        }

        .text {
            font-size: 1.1rem;
            color: #7D0A0A;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .subtext {
            font-size: 1rem;
            color: #DAA520;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background: #fffaf3;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .card-title-red {
            color: #B22222;
        }

        .card-title-orange {
            color: #E67E22;
            transition: color 0.3s ease;
        }

        .card-title-orange:hover {
            color: #D35400;
        }

        .accordion-button.dagang {
            background: linear-gradient(90deg, #FF5733, #B22222);
            color: white;
        }

        .accordion-button.produksi {
            background: linear-gradient(90deg, #FF8C00, #FF4500);
            color: white;
        }

        .accordion-button.jasa {
            background: linear-gradient(90deg, #FFD700, #FFA500);
            color: white;
        }

        .accordion-button.lainnya {
            background: linear-gradient(90deg, #FFB347, #FF6347);
            color: white;
        }

        .accordion-button {
            font-weight: bold;
            border-radius: 12px !important;
            margin-bottom: 5px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .accordion-button:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .accordion-body {
            background-color: #ffffdf;
            color: #333;
            border-radius: 0 0 12px 12px;
            padding: 20px;
        }

        .alert-primary {
            background: linear-gradient(90deg, #FF5733, #B22222);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 16px;
            padding: 20px;
        }

        .accordion-flush .accordion-item {
            border: none;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <!-- Judul Halaman -->
        <h1 class="mb-5 text-center judul-app">
            <img src="/storage/images/logo.png" alt="Logo Kedaton" class="logo-bounce mb-2" width="80"><br>
            <strong>KEDATON</strong><br>
            <small class="text">Kanal Ekonomi dan Data Usaha Terpeta Online</small>
        </h1>

        <div class="row g-4">
            <!-- Tentang Aplikasi -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h4 class="card-title card-title-red mb-3">
                            <i class="fa-solid fa-globe"></i> Tentang WebGIS
                        </h4>
                        <p class="text-justify">
                            <strong>KEDATON</strong> adalah sebuah akronim dari <strong>Kanal Ekonomi dan Data Usaha Terpeta
                                Online</strong> yang sekaligus
                            mengusung kearifan lokal Lampung. Dalam budaya Lampung, “kedaton” merujuk pada pusat
                            pemerintahan adat atau istana, simbol kewibawaan dan pengelolaan. Nama ini dipilih untuk
                            merepresentasikan aplikasi sebagai pusat informasi spasial UMKM di Bandar Lampung. Layaknya
                            kedaton yang menjadi pusat kendali dan pengetahuan, webGIS ini menjadi wadah utama dalam
                            menyajikan, mengelola, dan memahami potensi ekonomi lokal secara digital dan terpeta.
                        </p>
                        <p class="text-justify">
                            Penggunaan sistem informasi berbasis spasial ini diharapkan dapat mendorong pertumbuhan UMKM di
                            Bandar Lampung dan meningkatkan ekonomi lokal secara digital.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tentang UMKM -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h4 class="card-title card-title-orange mb-3">
                            <i class="fa-solid fa-chart-line"></i> UMKM di Bandar Lampung
                        </h4>
                        <p class="text-justify">
                            Kota Bandar Lampung memiliki ribuan pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) yang tersebar
                            di berbagai sektor seperti kuliner, kerajinan, fashion, produksi, jasa, hingga teknologi
                            kreatif.
                            Berdasarkan data dari <strong>Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Provinsi
                                Lampung</strong>,
                            tercatat lebih dari 14.000 UMKM aktif di wilayah ini, menjadikannya sebagai salah satu pilar
                            utama
                            penggerak perekonomian lokal. Dalam konteks inilah, upaya pemetaan dan digitalisasi data UMKM
                            menjadi langkah strategis yang penting dilakukan, guna mendukung perencanaan pembangunan
                            yang lebih tepat sasaran, meningkatkan efektivitas bantuan dan pendampingan, serta memperluas
                            akses pasar dan promosi UMKM secara digital di era transformasi teknologi saat ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Interaktif Accordion -->
        <div class="mt-5">
            <h4 class="fw-bold mb-3 text-center text-danger"><i class="fa-solid fa-list"></i> Kategori UMKM</h4>

            <div class="accordion accordion-flush shadow-sm" id="accordionUMKM">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button dagang" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Dagang
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionUMKM">
                        <div class="accordion-body">
                            Tersedia aneka jenis perdagangan khas Lampung maupun umum: keripik pisang, kemplang, sambal
                            Lampung, kopi robusta, hingga warung klontong.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button produksi" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Produksi
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionUMKM">
                        <div class="accordion-body">
                            Tapis Lampung, batik Lampung, anyaman bambu, yang menjadi favorit wisatawan sebagai oleh-oleh.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button jasa" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Jasa
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionUMKM">
                        <div class="accordion-body">
                            Salon, percetakan, fotografi, desain grafis, pengembangan aplikasi, hingga kursus keterampilan
                            digital.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button lainnya" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Lainnya
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionUMKM">
                        <div class="accordion-body">
                            Bentuk usaha lain seperti usaha berbasis digital, seni pertunjukan, atau edukasi non-formal.
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <small class="subtext">📊 Data UMKM akan terus diperbarui secara berkala melalui sistem aplikasi.</small>
            </div>
        </div>

        <!-- Ajakan -->
        <div class="alert alert-primary shadow mt-5 text-center">
            Mari dukung perkembangan UMKM Bandar Lampung melalui teknologi & inovasi!
        </div>
    </div>
@endsection
