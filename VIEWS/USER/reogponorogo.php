<?php
session_start();
include '../../BACKEND/config.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festival Nasional Reog Ponorogo — Pawerti</title>
    <link rel="stylesheet" href="../../CSS/deskripsievent.css">
</head>

<body>
    <?php include '../COMPONENTS/navbar.php'; ?>

        <!-- Deskripsi Event -->
    <section class="deskripsi-event" id="deskripsi-event">
        <h2>Deskripsi <br> Event</h2>
        <div class="event-container">
            <div class="event-text">
                <h3>Festival Nasional Reog Ponorogo</h3>
                <p><strong>Festival Nasional Reog Ponorogo</strong></p>
                <p>
                    Nasional Reog Ponorogo adalah acara tahunan di Kabupaten Ponorogo, Jawa Timur.
                    Acara ini merupakan kompetisi dan pameran seni yang bertujuan untuk melestarikan dan
                    mempromosikan seni Reog Ponorogo, salah satu seni budaya di Indonesia.
                </p>
                <p>
                   Para Peserta dari berbagai daerah menampilkan pertunjukan Reog terbaik mereka. Penilaian
                   meliputi gerak tari, kostum, dan musik pengiring yang unik. Festival inijuga sering
                   dimeriahkan dengan acara pendukung seperti pameran dan karnaval.
                </p>
                <p>
                   Festival ini berperan penting dalam menjaga kelangsungan seni Reog Ponorogo sebagai warisan budaya. Event ini tidak hanya menjadi ajang unjuk kebolehan, tetapi juga sarana edukasi dan promosi budaya kepada masyarakat luas.
                </p>
                <button class="btn-selengkapnya">Baca Selengkapnya</button>
            </div>

            <div class="event-image">
                <img src="../../images/reogponorogo.png" alt="Seblang OlehSari">
            </div>
        </div>
    </section>

    <!-- Informasi Event -->
    <section class="info-event">
        <div class="info-card tiket">
            <h3>Dapatkan<br>Tiket</h3>
        </div>
        <div class="info-card">
            <img src="../../images/dateicon.png" alt="Tanggal">
            <p>27 Juni 2026</p>
        </div>
        <div class="info-card">
            <img src="../../images/locationicon.png" alt="Lokasi">
            <p>Panggung Utama Alun-alun Kabupaten Ponorogo</p>
        </div>
        <div class="info-card">
            <img src="../../images/peopleicon.png" alt="Penonton">
            <p>4.000 Penonton</p>
        </div>
    </section>

    <!-- Partisipasi -->
    <section class="partisipasi">
        <h2>Partisipasi</h2>
        <div class="partisipasi-cards">
            <div class="card">
                <div class="card-image">
                    <img src="../../images/faceicon.png" alt="Pelatihan Tari">
                </div>
                <h4>Pelatihan Tari</h4>
                <p>Ikuti pelatihan tari tradisional dari Jawa seperti tari di Yogya dll.</p>
                <button>Gabung Pelatihan</button>
            </div>
            <div class="card">
                <div class="card-image">
                    <img src="../../images/musikicon.png" alt="Kursus Musik">
                </div>
                <h4>Kursus Musik</h4>
                <p>Pelajari alat musik tradisional Jawa seperti gamelan, angklung, dan sasando.</p>
                <button>Mulai Belajar</button>
            </div>
            <div class="card">
                <div class="card-image">
                    <img src="../../images/painticon.png" alt="Workshop Seni">
                </div>
                <h4>Workshop Seni</h4>
                <p>Belajar membuat batik, ukiran kayu, dan kerajinan tradisional lainnya.</p>
                <button>Daftar Workshop</button>
            </div>
            <div class="card">
                <div class="card-image">
                    <img src="../../images/bookicon.png" alt="Dokumentasi Budaya">
                </div>
                <h4>Dokumentasi Budaya</h4>
                <p>Berpartisipasi dalam pelestarian budaya melalui dokumentasi digital.</p>
                <button>Kontribusi</button>
            </div>
        </div>
    </section>
    <?php include '../COMPONENTS/footer.php'; ?>
</body>
</html>