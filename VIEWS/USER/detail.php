<?php
include '../../BACKEND/config.php';

if (!isset($_GET['id'])) {
    die("Event tidak ditemukan");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT e.*, k.nama_kategori
    FROM events e
    LEFT JOIN kategori k ON e.kategori_id = k.id
    WHERE e.id = '$id'
");

$event = mysqli_fetch_assoc($query);

if (!$event) {
    die("Event tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Workshop Batik Tulis – Pawarti</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --cream: #F7F3EE;
    --warm-white: #FDFAF6;
    --brown-deep: #3D1F0E;
    --brown-mid: #7A3B1E;
    --brown-light: #C0855A;
    --accent-gold: #C9A84C;
    --accent-terracotta: #B85C38;
    --text-body: #4A3728;
    --text-muted: #8A7060;
    --border: #E0D4C8;
    --dark-maroon: #5C1A1A;
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--warm-white);
    color: var(--text-body);
    line-height: 1.7;
  }


  /* BREADCRUMB */
  .breadcrumb {
    padding: 14px 5%;
    background: var(--cream);
    border-bottom: 1px solid var(--border);
    font-size: 0.8rem;
    color: var(--text-muted);
  }
  .breadcrumb a { color: var(--text-muted); text-decoration: none; }
  .breadcrumb a:hover { color: var(--brown-mid); text-decoration: underline; }
  .breadcrumb span { margin: 0 6px; }

  /* HERO */
  .hero {
    background: var(--cream);
    padding: 40px 5% 0;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
  }
  .hero-meta { padding-bottom: 40px; }
  .badge-unggulan {
    display: inline-block;
    background: var(--accent-gold);
    color: #5C3D00;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 4px;
    margin-bottom: 14px;
  }
  .hero-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem;
    font-weight: 700;
    color: var(--brown-deep);
    line-height: 1.25;
    margin-bottom: 10px;
  }
  .hero-tagline {
    font-size: 1rem;
    color: var(--text-muted);
    margin-bottom: 18px;
  }
  .organizer-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
  }
  .organizer-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--brown-mid);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: 0.8rem;
  }
  .organizer-name { font-size: 0.9rem; font-weight: 600; color: var(--brown-deep); }
  .organizer-label { font-size: 0.78rem; color: var(--text-muted); }

  .event-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 28px;
  }
  .stat-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.875rem;
    color: var(--text-body);
  }
  .stat-icon {
    width: 32px; height: 32px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem;
  }
  .stat-label { font-size: 0.72rem; color: var(--text-muted); display: block; }
  .stat-value { font-weight: 600; display: block; color: var(--brown-deep); }

  .hero-image-wrap {
    border-radius: 16px;
    overflow: hidden;
    height: 280px;
    background: #ddd;
    position: relative;
    align-self: start;
  }
  .hero-image-wrap img {
    width: 100%; height: 100%; object-fit: cover;
  }
  .hero-img-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, #c9845a 0%, #7a3b1e 60%, #3d1f0e 100%);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.3);
    font-size: 3rem;
    font-family: 'Playfair Display', serif;
  }

  /* STICKY BOOK CARD */
  .layout-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 36px 5%;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 3rem;
    align-items: start;
  }

  .book-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: 28px;
    position: sticky;
    top: 80px;
    box-shadow: 0 4px 24px rgba(61,31,14,0.07);
  }
  .price-main {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--dark-maroon);
    margin-bottom: 4px;
  }
  .price-sub { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 20px; }

  .seat-bar-wrap { margin-bottom: 20px; }
  .seat-bar-label {
    display: flex; justify-content: space-between;
    font-size: 0.8rem; color: var(--text-muted); margin-bottom: 6px;
  }
  .seat-bar-track {
    height: 6px; background: var(--border); border-radius: 99px; overflow: hidden;
  }
  .seat-bar-fill {
    height: 100%; background: var(--accent-terracotta); border-radius: 99px;
    width: 98%; /* 98 dari 100 terisi */
  }
  .seat-count {
    text-align: center;
    font-size: 0.82rem;
    color: var(--accent-terracotta);
    font-weight: 600;
    margin-top: 6px;
  }

  .qty-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 10px 16px;
    margin-bottom: 14px;
  }
  .qty-btn {
    width: 32px; height: 32px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--cream);
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    color: var(--brown-deep);
  }
  .qty-num { font-weight: 700; font-size: 1.1rem; color: var(--brown-deep); }
  .qty-label { font-size: 0.8rem; color: var(--text-muted); }

  .btn-pesan {
    width: 100%;
    padding: 14px;
    background: var(--dark-maroon);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    margin-bottom: 12px;
    letter-spacing: 0.3px;
  }
  .btn-pesan:hover { background: var(--brown-mid); }
  .btn-wishlist {
    width: 100%;
    padding: 12px;
    background: transparent;
    color: var(--dark-maroon);
    border: 1.5px solid var(--dark-maroon);
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
  }
  .card-meta-list {
    margin-top: 22px;
    border-top: 1px solid var(--border);
    padding-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .card-meta-item {
    display: flex; gap: 10px; align-items: flex-start;
    font-size: 0.82rem; color: var(--text-muted);
  }
  .card-meta-item strong { color: var(--text-body); display: block; }

  /* CONTENT */
  .content-section { margin-bottom: 36px; }
  .section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--brown-deep);
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--cream);
  }
  .section-title::after {
    content: '';
    display: block;
    width: 40px;
    height: 3px;
    background: var(--accent-gold);
    margin-top: 8px;
  }

  .prose p { margin-bottom: 1rem; font-size: 0.95rem; color: var(--text-body); }
  .prose ul { padding-left: 1.2rem; margin-bottom: 1rem; }
  .prose ul li { margin-bottom: 6px; font-size: 0.95rem; color: var(--text-body); }

  /* TIMELINE ACARA */
  .timeline { display: flex; flex-direction: column; gap: 0; }
  .timeline-item {
    display: flex;
    gap: 16px;
    position: relative;
  }
  .timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 32px;
    bottom: 0;
    width: 2px;
    background: var(--border);
  }
  .timeline-dot {
    width: 32px; height: 32px; min-width: 32px;
    background: var(--cream);
    border: 2px solid var(--accent-gold);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--brown-mid);
    position: relative;
    z-index: 1;
  }
  .timeline-body { padding: 4px 0 24px; }
  .timeline-time { font-size: 0.75rem; font-weight: 700; color: var(--accent-gold); letter-spacing: 0.5px; text-transform: uppercase; }
  .timeline-label { font-size: 0.95rem; font-weight: 600; color: var(--brown-deep); }
  .timeline-desc { font-size: 0.85rem; color: var(--text-muted); margin-top: 3px; }

  /* WHAT YOU'LL GET */
  .benefits-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .benefit-item {
    background: var(--cream);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
  }
  .benefit-icon {
    width: 36px; height: 36px; min-width: 36px;
    background: var(--accent-gold);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
  }
  .benefit-title { font-weight: 600; font-size: 0.875rem; color: var(--brown-deep); margin-bottom: 3px; }
  .benefit-desc { font-size: 0.8rem; color: var(--text-muted); }

  /* SPEAKER */
  .speaker-card {
    background: var(--cream);
    border-radius: 14px;
    padding: 22px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
  }
  .speaker-avatar {
    width: 72px; height: 72px; min-width: 72px;
    border-radius: 12px;
    background: var(--brown-mid);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 700;
  }
  .speaker-name { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--brown-deep); margin-bottom: 3px; }
  .speaker-role { font-size: 0.82rem; color: var(--accent-terracotta); font-weight: 600; margin-bottom: 8px; }
  .speaker-bio { font-size: 0.85rem; color: var(--text-muted); }

  /* LOKASI */
  .lokasi-box {
    background: var(--cream);
    border-radius: 14px;
    padding: 20px;
    display: flex;
    gap: 16px;
  }
  .map-placeholder {
    width: 130px; min-width: 130px; height: 100px;
    background: #d9cfc6;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted);
    font-size: 1.5rem;
  }
  .lokasi-info { flex: 1; }
  .lokasi-name { font-weight: 700; font-size: 0.95rem; color: var(--brown-deep); margin-bottom: 4px; }
  .lokasi-address { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 10px; }
  .btn-maps {
    display: inline-block;
    padding: 7px 16px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 7px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dark-maroon);
    text-decoration: none;
    cursor: pointer;
  }

  /* RELATED EVENTS */
  .related-section {
    background: var(--cream);
    padding: 40px 5%;
    margin-top: 20px;
  }
  .related-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--brown-deep);
    margin-bottom: 24px;
    text-align: center;
  }
  .related-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1200px; margin: 0 auto; }
  .related-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid var(--border);
  }
  .related-img {
    height: 140px;
    background: linear-gradient(135deg, #c9845a, #3d1f0e);
    position: relative;
  }
  .related-img-2 { background: linear-gradient(135deg, #4a7c59, #1a3d2a); }
  .related-img-3 { background: linear-gradient(135deg, #5a6cc9, #1a2a6c); }
  .related-category {
    position: absolute;
    bottom: 10px; left: 12px;
    background: rgba(0,0,0,0.45);
    color: #fff; font-size: 0.7rem; font-weight: 600;
    padding: 3px 10px; border-radius: 4px;
    letter-spacing: 0.5px; text-transform: uppercase;
  }
  .related-body { padding: 16px; }
  .related-event-title { font-weight: 700; font-size: 0.95rem; color: var(--brown-deep); margin-bottom: 6px; }
  .related-meta { font-size: 0.78rem; color: var(--text-muted); margin-bottom: 10px; }
  .related-price { font-weight: 700; color: var(--dark-maroon); font-size: 0.95rem; }

  /* FAQ */
  .faq-item {
    border-bottom: 1px solid var(--border);
    padding: 16px 0;
  }
  .faq-question {
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--brown-deep);
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
  }
  .faq-question:hover { color: var(--dark-maroon); }
  .faq-answer {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 8px;
    display: none;
  }
  .faq-item.open .faq-answer { display: block; }
  .faq-arrow { transition: transform 0.2s; font-size: 1rem; }
  .faq-item.open .faq-arrow { transform: rotate(180deg); }
</style>
</head>
<body>
    <!-- Navbar -->
    <?php include '../COMPONENTS/navbar.php'; ?>
    <?php include '../COMPONENTS/user_modals.php'; ?>

<!-- BREADCRUMB -->
<!--<div class="breadcrumb">
  <a href="#">Beranda</a>
  <span>›</span>
  <a href="#">Event</a>
  <span>›</span>
  <a href="#">Workshop Budaya</a>
  <span>›</span>
  Workshop Batik Tulis Bersama Maestro
</div>-->

<!-- HERO -->
<div style="background: var(--cream);">
  <div class="hero">
    <div class="hero-meta">
      <div class="badge-unggulan">⭐ Unggulan</div>
      <h1 class="hero-title">Workshop Batik Tulis<br>Bersama Maestro</h1>
      <p class="hero-tagline">Pelajari seni membatik langsung dari pengrajin batik Solo berumur 40 tahun pengalaman</p>

      <div class="organizer-row">
        <div class="organizer-avatar">PW</div>
        <div>
          <div class="organizer-name">Pawarti Cultural Arts</div>
          <div class="organizer-label">Penyelenggara Terverifikasi</div>
        </div>
      </div>

      <div class="event-stats">
        <div class="stat-item">
          <div class="stat-icon">📅</div>
          <div>
            <span class="stat-label">Tanggal</span>
            <span class="stat-value">10 Juli 2026</span>
          </div>
        </div>
        <div class="stat-item">
          <div class="stat-icon">🕘</div>
          <div>
            <span class="stat-label">Waktu</span>
            <span class="stat-value">09.00 – 16.00 WIB</span>
          </div>
        </div>
        <div class="stat-item">
          <div class="stat-icon">📍</div>
          <div>
            <span class="stat-label">Lokasi</span>
            <span class="stat-value">Solo, Jawa Tengah</span>
          </div>
        </div>
        <div class="stat-item">
          <div class="stat-icon">👥</div>
          <div>
            <span class="stat-label">Kapasitas</span>
            <span class="stat-value">100 Peserta</span>
          </div>
        </div>
      </div>

      <!-- Share row -->
      <div style="display: flex; gap: 10px; align-items: center;">
        <span style="font-size:0.8rem; color: var(--text-muted);">Bagikan:</span>
        <button style="padding:6px 14px; border:1px solid var(--border); border-radius:6px; background:#fff; font-size:0.8rem; cursor:pointer; color: var(--text-body);">📋 Salin Tautan</button>
        <button style="padding:6px 14px; border:1px solid var(--border); border-radius:6px; background:#fff; font-size:0.8rem; cursor:pointer; color: var(--text-body);">📱 WhatsApp</button>
      </div>
    </div>

    <!-- Hero image -->
    <div class="hero-image-wrap">
      <div class="hero-img-placeholder">
        <span style="opacity:0.5; font-size:4rem;">🎨</span>
      </div>
      <!-- Ganti dengan: <img src="foto-event.jpg" alt="Workshop Batik"> -->
    </div>
  </div>
</div>

<!-- MAIN LAYOUT -->
<div class="layout-wrapper">

  <!-- LEFT CONTENT -->
  <main>

    <!-- DESKRIPSI -->
    <section class="content-section">
      <h2 class="section-title">Tentang Event Ini</h2>
      <div class="prose">
        <p>Workshop Batik Tulis adalah program intensif satu hari yang dirancang untuk memperkenalkan peserta pada seni membatik tradisional Jawa secara mendalam. Dipandu langsung oleh <strong>Pak Djoko Santoso</strong>, seorang maestro batik asal Laweyan, Solo, dengan pengalaman lebih dari 40 tahun.</p>
        <p>Batik tulis adalah salah satu warisan budaya Indonesia yang telah diakui UNESCO sejak tahun 2009. Dalam workshop ini, peserta tidak sekadar menonton — melainkan benar-benar menciptakan karya batik mereka sendiri dari nol, mulai dari menggambar pola hingga proses pelorodan.</p>
        <p>Workshop ini terbuka untuk semua kalangan, dari pemula hingga yang sudah pernah mencoba membatik. Tidak diperlukan keahlian seni khusus — hanya semangat belajar dan kecintaan pada budaya Nusantara.</p>
      </div>
    </section>

    <!-- YANG AKAN KAMU DAPATKAN -->
    <section class="content-section">
      <h2 class="section-title">Yang Akan Kamu Dapatkan</h2>
      <div class="benefits-grid">
        <div class="benefit-item">
          <div class="benefit-icon">🖊️</div>
          <div>
            <div class="benefit-title">Praktik Langsung Membatik</div>
            <div class="benefit-desc">Gunakan canting asli dan lilin batik (malam) bersama maestro</div>
          </div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon">🎁</div>
          <div>
            <div class="benefit-title">Karya Batik Dibawa Pulang</div>
            <div class="benefit-desc">Hasil karya batik sendiri sebagai kenang-kenangan</div>
          </div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon">🍱</div>
          <div>
            <div class="benefit-title">Makan Siang & Snack</div>
            <div class="benefit-desc">Hidangan tradisional Jawa disiapkan untuk peserta</div>
          </div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon">📜</div>
          <div>
            <div class="benefit-title">Sertifikat Partisipasi</div>
            <div class="benefit-desc">Sertifikat resmi dari Pawarti Cultural Arts</div>
          </div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon">📸</div>
          <div>
            <div class="benefit-title">Dokumentasi Foto</div>
            <div class="benefit-desc">Foto profesional selama workshop dikirim via email</div>
          </div>
        </div>
        <div class="benefit-item">
          <div class="benefit-icon">📚</div>
          <div>
            <div class="benefit-title">Buku Panduan Batik</div>
            <div class="benefit-desc">Panduan teknik batik tulis untuk dipelajari di rumah</div>
          </div>
        </div>
      </div>
    </section>

    <!-- RUNDOWN ACARA -->
    <section class="content-section">
      <h2 class="section-title">Rundown Acara</h2>
      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-dot">1</div>
          <div class="timeline-body">
            <div class="timeline-time">08.30 – 09.00</div>
            <div class="timeline-label">Registrasi & Welcome Drink</div>
            <div class="timeline-desc">Check-in peserta, pembagian perlengkapan, dan minuman selamat datang</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot">2</div>
          <div class="timeline-body">
            <div class="timeline-time">09.00 – 10.00</div>
            <div class="timeline-label">Pengantar Sejarah Batik</div>
            <div class="timeline-desc">Sesi presentasi interaktif tentang sejarah, filosofi, dan jenis-jenis batik Jawa</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot">3</div>
          <div class="timeline-body">
            <div class="timeline-time">10.00 – 12.00</div>
            <div class="timeline-label">Sesi Membatik Sesi 1: Pencantingan</div>
            <div class="timeline-desc">Praktik menggunakan canting untuk membubuhkan malam pada kain, dipandu maestro</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot">4</div>
          <div class="timeline-body">
            <div class="timeline-time">12.00 – 13.00</div>
            <div class="timeline-label">Istirahat & Makan Siang</div>
            <div class="timeline-desc">Makan siang dengan menu tradisional Jawa di area sanggar</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot">5</div>
          <div class="timeline-body">
            <div class="timeline-time">13.00 – 15.30</div>
            <div class="timeline-label">Sesi Membatik Sesi 2: Pewarnaan</div>
            <div class="timeline-desc">Proses pewarnaan kain dan pelorodan (menghilangkan malam) untuk menghasilkan motif</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot">6</div>
          <div class="timeline-body">
            <div class="timeline-time">15.30 – 16.00</div>
            <div class="timeline-label">Penutupan & Foto Bersama</div>
            <div class="timeline-desc">Pembagian sertifikat, sesi foto bersama, dan pengemasan karya batik</div>
          </div>
        </div>
      </div>
    </section>

    <!-- NARASUMBER -->
    <section class="content-section">
      <h2 class="section-title">Narasumber</h2>
      <div class="speaker-card">
        <div class="speaker-avatar">DS</div>
        <div>
          <div class="speaker-name">Pak Djoko Santoso</div>
          <div class="speaker-role">Maestro Batik Tulis, Laweyan – Solo</div>
          <div class="speaker-bio">Pak Djoko adalah pengrajin batik tulis generasi ketiga dari keluarga pengrajin batik di Kampung Laweyan, Solo. Dengan pengalaman lebih dari 40 tahun, beliau telah melatih ratusan pengrajin muda dan karya-karyanya telah dipamerkan di berbagai festival budaya internasional, termasuk di Paris dan Tokyo. Beliau juga penerima penghargaan Maestro Seni Tradisi dari Kemendikbud RI.</div>
        </div>
      </div>
    </section>

    <!-- LOKASI -->
    <section class="content-section">
      <h2 class="section-title">Lokasi Event</h2>
      <div class="lokasi-box">
        <div class="map-placeholder">🗺️</div>
        <div class="lokasi-info">
          <div class="lokasi-name">Sanggar Batik Laweyan Heritage</div>
          <div class="lokasi-address">Jl. Dr. Rajiman No. 521, Laweyan,<br>Kota Solo, Jawa Tengah 57142</div>
          <a class="btn-maps" href="https://maps.google.com" target="_blank">🧭 Lihat di Google Maps</a>
        </div>
      </div>
      <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 12px;">📌 Tersedia area parkir kendaraan roda 2 dan roda 4. Dapat diakses dengan Grab/Gojek.</p>
    </section>

    <!-- SYARAT & KETENTUAN -->
    <section class="content-section">
      <h2 class="section-title">Syarat & Ketentuan</h2>
      <div class="prose">
        <ul>
          <li>Peserta minimal berusia 15 tahun. Anak dibawah 15 tahun harus didampingi orang tua/wali.</li>
          <li>Harap datang 30 menit sebelum acara dimulai untuk proses registrasi.</li>
          <li>Kenakan pakaian yang tidak keberatan terkena noda pewarna (disarankan pakaian gelap).</li>
          <li>Tiket tidak dapat dikembalikan (non-refundable). Penukaran tanggal dapat dilakukan H-3 sebelum acara.</li>
          <li>Perlengkapan membatik (canting, malam, kain) telah disediakan oleh penyelenggara.</li>
          <li>Dokumentasi foto dan video untuk keperluan pribadi diperbolehkan.</li>
        </ul>
      </div>
    </section>

    <!-- FAQ -->
    <section class="content-section">
      <h2 class="section-title">Pertanyaan Umum</h2>
      <div>
        <div class="faq-item open">
          <div class="faq-question" onclick="toggleFaq(this)">
            Apakah saya perlu memiliki pengalaman membatik sebelumnya?
            <span class="faq-arrow">▾</span>
          </div>
          <div class="faq-answer">Tidak sama sekali! Workshop ini dirancang untuk semua level, mulai dari pemula hingga yang sudah berpengalaman. Narasumber kami akan memandu dengan sabar dari tahap paling dasar.</div>
        </div>
        <div class="faq-item">
          <div class="faq-question" onclick="toggleFaq(this)">
            Apa yang perlu saya bawa?
            <span class="faq-arrow">▾</span>
          </div>
          <div class="faq-answer">Cukup bawa diri dan semangat! Semua perlengkapan membatik sudah disediakan. Disarankan memakai pakaian yang tidak keberatan terkena noda. Anda juga bisa membawa kamera untuk mendokumentasikan proses.</div>
        </div>
        <div class="faq-item">
          <div class="faq-question" onclick="toggleFaq(this)">
            Apakah tiket bisa ditukar atau dikembalikan?
            <span class="faq-arrow">▾</span>
          </div>
          <div class="faq-answer">Tiket bersifat non-refundable. Namun, Anda dapat menukar ke tanggal event lain selambat-lambatnya 3 hari sebelum acara dengan menghubungi tim Pawarti melalui WhatsApp atau email.</div>
        </div>
        <div class="faq-item">
          <div class="faq-question" onclick="toggleFaq(this)">
            Apakah tersedia parkir di lokasi?
            <span class="faq-arrow">▾</span>
          </div>
          <div class="faq-answer">Ya, tersedia area parkir untuk kendaraan roda 2 dan roda 4 di sekitar Sanggar Batik Laweyan Heritage. Lokasi juga mudah dijangkau dengan transportasi online.</div>
        </div>
        <div class="faq-item">
          <div class="faq-question" onclick="toggleFaq(this)">
            Apakah ada batasan usia untuk mengikuti workshop?
            <span class="faq-arrow">▾</span>
          </div>
          <div class="faq-answer">Workshop terbuka untuk peserta minimal usia 15 tahun. Peserta di bawah 15 tahun diperbolehkan hadir jika didampingi orang tua atau wali yang juga terdaftar sebagai peserta.</div>
        </div>
      </div>
    </section>

  </main>

  <!-- RIGHT STICKY CARD -->
  <aside>
    <div class="book-card">
      <div class="price-main">Rp 150.000</div>
      <div class="price-sub">per orang · termasuk makan siang & perlengkapan</div>

      <div class="seat-bar-wrap">
        <div class="seat-bar-label">
          <span>Ketersediaan Kursi</span>
          <span style="color: var(--accent-terracotta); font-weight: 600;">Hampir Habis!</span>
        </div>
        <div class="seat-bar-track">
          <div class="seat-bar-fill"></div>
        </div>
        <div class="seat-count">Tersisa 2 kursi dari 100</div>
      </div>

      <div class="qty-row">
        <div>
          <div class="qty-label">Jumlah Tiket</div>
        </div>
        <div style="display:flex; align-items:center; gap: 12px;">
          <button class="qty-btn" onclick="changeQty(-1)">−</button>
          <span class="qty-num" id="qty">1</span>
          <button class="qty-btn" onclick="changeQty(1)">+</button>
        </div>
      </div>

      <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 16px; padding: 10px 0; border-top: 1px solid var(--border);">
        <span style="color: var(--text-muted);">Total</span>
        <span id="total-price" style="font-weight: 700; color: var(--brown-deep);">Rp 150.000</span>
      </div>

      <button class="btn-pesan">🎟️ Pesan Sekarang</button>
      <button class="btn-wishlist">♡ Simpan ke Wishlist</button>

      <div class="card-meta-list">
        <div class="card-meta-item">
          <span>📅</span>
          <div><strong>Jumat, 10 Juli 2026</strong> 09.00 – 16.00 WIB</div>
        </div>
        <div class="card-meta-item">
          <span>📍</span>
          <div><strong>Sanggar Batik Laweyan</strong> Solo, Jawa Tengah</div>
        </div>
        <div class="card-meta-item">
          <span>🔄</span>
          <div>Penukaran tanggal dapat dilakukan H-3 sebelum acara</div>
        </div>
        <div class="card-meta-item">
          <span>💬</span>
          <div>Ada pertanyaan? <a href="#" style="color: var(--dark-maroon); font-weight: 600;">Chat Penyelenggara</a></div>
        </div>
      </div>
    </div>
  </aside>

</div>

<!-- RELATED EVENTS -->
<div class="related-section">
  <h2 class="related-title">Event Budaya Lainnya</h2>
  <div class="related-grid">
    <div class="related-card">
      <div class="related-img" style="background: linear-gradient(135deg, #c9845a, #3d1f0e);">
        <span class="related-category">Seni Pertunjukan</span>
      </div>
      <div class="related-body">
        <div class="related-event-title">Pasar Rakyat Surabaya</div>
        <div class="related-meta">📍 Surabaya &nbsp;·&nbsp; 15 Juni 2026</div>
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <div class="related-price">Rp 10.000</div>
          <span style="font-size:0.75rem; background:#ffeaea; color:#a30000; padding:3px 10px; border-radius:4px; font-weight:600;">Tiket Habis</span>
        </div>
      </div>
    </div>
    <div class="related-card">
      <div class="related-img" style="background: linear-gradient(135deg, #4a7c59, #1a3d2a);">
        <span class="related-category">Workshop Budaya</span>
      </div>
      <div class="related-body">
        <div class="related-event-title">Festival Reog Ponorogo</div>
        <div class="related-meta">📍 Ponorogo &nbsp;·&nbsp; 20 Juni 2026</div>
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <div class="related-price">Rp 50.000</div>
          <span style="font-size:0.75rem; color: var(--text-muted);">Sisa 98 kursi</span>
        </div>
      </div>
    </div>
    <div class="related-card">
      <div class="related-img" style="background: linear-gradient(135deg, #8b6914, #4a3800);">
        <span class="related-category">Kuliner Tradisional</span>
      </div>
      <div class="related-body">
        <div class="related-event-title">Kelas Memasak Gudeg Jogja</div>
        <div class="related-meta">📍 Yogyakarta &nbsp;·&nbsp; 25 Juli 2026</div>
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <div class="related-price">Rp 85.000</div>
          <span style="font-size:0.75rem; color: var(--text-muted);">Sisa 30 kursi</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="footer-bottom">© 2024 Pawarti. Semua hak cipta dilindungi.</div>

<script>
  let qty = 1;
  const basePrice = 150000;

  function changeQty(delta) {
    qty = Math.max(1, Math.min(2, qty + delta)); // max 2 (sisa kursi)
    document.getElementById('qty').textContent = qty;
    document.getElementById('total-price').textContent = 'Rp ' + (qty * basePrice).toLocaleString('id-ID');
  }

  function toggleFaq(el) {
    const item = el.parentElement;
    item.classList.toggle('open');
  }
</script>

 <?php include '../COMPONENTS/footer.php'; ?>
</body>
</html>