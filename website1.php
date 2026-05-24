<?php
session_start();
include 'koneksi.php';

// Ambil data Kategori
$query_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
$query_event = mysqli_query($koneksi, "SELECT * FROM events ORDER BY id DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawarti – Jelajahi Kekayaan Budaya Jawa</title>
  <link rel="stylesheet" href="website.css" />
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar" id="navbar">
    <div class="navbar-logo">
      <div class="logo-icon">
       <img src="./images/navba.png" alt="logo pawarti">
      </div>
      <span>Pawarti</span>
    </div>

    <!-- Hamburger Button (mobile) -->
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <ul class="navbar-nav" id="navMenu">
      <li><a href="#hero" onclick="closeMenu()">Beranda</a></li>
      <li><a href="#events" onclick="closeMenu()">Event</a></li>
      <li><a href="tentangkami.html" onclick="closeMenu()">Tentang Kami</a></li>
      <li><a href="#kontak" onclick="closeMenu()">Kontak</a></li>
      <li><a href="#" class="btn-login" onclick="closeMenu()">Login</a></li>
    </ul>
  </nav>

  <!-- Overlay mobile menu -->
  <div class="nav-overlay" id="navOverlay" onclick="closeMenu()"></div>

  <!-- ===== HERO ===== -->
  <section class="hero">
    <div class="hero-bg"><img src="./images/framehero.png" alt="Hero Background" width="1900" height="500"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <h1>
        Jelajahi Kekayaan
        <span>Budaya Jawa</span>
      </h1>
      <p>
        Temukan dan ikuti berbagai event budaya Jawa, dari pertunjukan wayang hingga
        workshop batik tradisional
      </p>
      <div class="hero-actions">
        <a href="#events" class="btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          Cari Event
        </a>
        <a href="partisipasi.html" class="btn-outline">Partisipasi</a>
      </div>
    </div>
  </section>

  <!-- ===== KATEGORI ===== -->
  <section class="section categories-section">
    <div class="section-header">
      <h2>Kategori Event Kebudayaan</h2>
      <p>Jelajahi berbagai kategori event budaya Jawa yang tersedia</p>
    </div>
    <div class="categories-grid">

      <div class="category-card">
        <div class="category-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a6 6 0 0 1 12 0v2"/></svg>
        </div>
        <span class="count">12 Event</span>
        <h3>Pertunjukan Seni</h3>
        <p>Wayang, tari klasik Jawa, dan pertunjukan seni budaya Jawa lainnya</p>
      </div>

      <div class="category-card">
        <div class="category-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </div>
        <span class="count">8 Event</span>
        <h3>Workshop Budaya</h3>
        <p>Workshop batik, keris, wayang, dan kerajinan tangan tradisional Jawa</p>
      </div>

      <div class="category-card">
        <div class="category-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
        </div>
        <span class="count">7 Event</span>
        <h3>Musik Tradisional</h3>
        <p>Gamelan, karawitan, campursari, dan seni musik tradisional Jawa</p>
      </div>

      <div class="category-card">
        <div class="category-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <span class="count">5 Event</span>
        <h3>Upacara Adat</h3>
        <p>Upacara adat, tradisi, dan ritual budaya Jawa yang sakral</p>
      </div>

    </div>
  </section>

  <!-- ===== EVENT POPULAR ===== -->
  <section class="section events-section" id="events">
    <div class="section-header">
      <h2>Event Popular</h2>
      <p>Event budaya Jawa pilihan yang tak boleh dilewatkan</p>
    </div>

    <div class="events-grid">

      <!-- Card 1 -->
      <div class="event-card">
        <div class="event-img">
          <img src="./images/swargaloka.png" alt="The Indonesia Ballet Gala" />
          <span class="event-badge badge-wayang">Sendratari</span>
          <div class="event-actions">
            <div class="icon-btn">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <div class="icon-btn">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </div>
          </div>
        </div>
        <div class="event-info">
          <h3>The Indonesia Ballet Gala</h3>
          <p> kreasi tari klasik yang berasal dari Yogyakarta, diciptakan oleh Bathara Saverigadi Dewantoro. Tarian ini menggabungkan keanggunan budaya tari Jawa dengan estetika balet klasik, dan bermakna sebagai ungkapan kasih sayang serta harapan dari sebuah janji suci.</p>
          <div class="event-meta">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Sabtu, 5 Desember 2024
            </span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Gedung Kesenian Jakarta
            </span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              320 peserta
            </span>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="event-card">
        <div class="event-img">
          <img src="./images/seblang.png" alt="Seblang oleh sari" />
          <span class="event-badge badge-tari">Tari</span>
          <div class="event-actions">
            <div class="icon-btn">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <div class="icon-btn">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </div>
          </div>
        </div>
        <div class="event-info">
          <h3>Seblang Oleh sari</h3>
          <p>ritual adat sakral masyarakat Suku Osing di Desa Olehsari, Banyuwangi. Digelar untuk bersih desa dan tolak bala, tradisi ini menampilkan seorang penari yang dirasuki roh leluhur (tidak sadarkan diri), menari dengan mata terpejam mengikuti irama gamelan, dan melempar selendang untuk mengajak penonton menari bersama.</p>
          <div class="event-meta">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Jumat, 19 Januari 2025
            </span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Pura Mangkunegaran, Solo
            </span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              180 peserta
            </span>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="event-card">
        <div class="event-img">
          <img src="./images/reogponorogo.png" alt="Festival Nasional Reog Ponorogo" />
          <span class="event-badge badge-festival">Festival</span>
          <div class="event-actions">
            <div class="icon-btn">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <div class="icon-btn">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </div>
          </div>
        </div>
        <div class="event-info">
          <h3>Festival Nasional Reog Ponorogo</h3>
          <p>festival budaya tahunan yang menampilkan kesenian tradisional Reog Ponorogo sebagai daya tarik utamanya. Digelar rutin sejak 1995 di Alun-Alun Ponorogo, acara ini memperebutkan Piala Presiden bergengsi dan menjadi bagian puncak dari perayaan rakyat Grebeg Suro.</p>
          <div class="event-meta">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Minggu, 3 Maret 2025
            </span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Candi Prambanan, Yogyakarta
            </span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              500 peserta
            </span>
          </div>
        </div>
      </div>

    </div>

    <div class="events-more">
      <a href="events.html" class="btn-more" style="display:inline-block;text-decoration:none;">Lihat Semua Event</a>
    </div>
  </section>

  <!-- ===== KONTAK ===== -->
  <section class="section contact-section" id="kontak">
    <div class="section-header">
      <h2>Kontak Kami</h2>
      <p>Ada pertanyaan tentang event, membeli tiket atau ingin berkolaborasi? Kami siap membantu anda.</p>
    </div>

    <div class="contact-grid">

      <!-- Info Kontak -->
      <div class="contact-info">
        <h3>Informasi Kontak</h3>
        <div class="contact-list">

          <div class="contact-item">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div class="contact-item-text">
              <p>Email</p>
              <p>info@budayajawa.id<br/>Email: pawarti@jawa.id</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.82 19 19.45 19.45 0 0 1 5 12.18 19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91A16 16 0 0 0 14.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div class="contact-item-text">
              <p>Telepon</p>
              <p>0800-5766-2459<br/>0812-7540-3241</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="contact-item-text">
              <p>Alamat</p>
              <p>Jl. Pemuda no.22, Kaum, Bon Kijon,<br/>Kota Malang, Jawa Timur</p>
            </div>
          </div>

        </div>

        <!-- Jam Operasional -->
        <div class="hours-box">
          <h4>Jam Operasional</h4>
          <table class="hours-table">
            <tr>
              <td>Senin – Jumat</td>
              <td>08.00 – 17.00 WIB</td>
            </tr>
            <tr>
              <td>Sabtu</td>
              <td>08.00 – 13.00 WIB</td>
            </tr>
            <tr>
              <td>Minggu</td>
              <td>Tutup</td>
            </tr>
          </table>
        </div>
      </div>

      <!-- Form Kirim Pesan -->
      <div class="contact-form">
        <h3>Kirim Pesan</h3>
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" placeholder="Masukkan nama lengkap" />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="Masukkan email" />
        </div>
        <div class="form-group">
          <label for="subjek">Subjek</label>
          <input type="text" id="subjek" placeholder="Masukkan subjek email" />
        </div>
        <div class="form-group">
          <label for="pesan">Pesan</label>
          <textarea id="pesan" placeholder="Tulis pesan anda di sini..."></textarea>
        </div>
        <button class="btn-submit">Kirim pesan sekarang</button>
      </div>

    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <footer>
    <div class="footer-grid">

      <div class="footer-brand">
        <div class="brand-logo">
          <div class="logo-icon">
            <img src="./images/logobg.png" alt="Logo Pawarti">
          </div>
          <span>Pawarti</span>
        </div>
        <p>Pawarti memperkenalkan kekayaan budaya Jawa melalui berbagai event seni yang inspiratif dan bermakna.</p>
      </div>

      <div class="footer-col">
        <h4>Kategori</h4>
        <ul>
          <li><a href="#">Pertunjukan Seni</a></li>
          <li><a href="#">Workshop Budaya</a></li>
          <li><a href="#">Musik Tradisional</a></li>
          <li><a href="#">Upacara Adat</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Kontak</h4>
        <div class="contact-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>info@pawarti.budayajawa@gmail.com</span>
        </div>
        <div class="contact-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.82 19 19.45 19.45 0 0 1 5 12.18 19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91A16 16 0 0 0 14.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <span>Telepon: 0800-5766-2459</span>
        </div>
        <div class="contact-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span>Jl Pemuda no.22 Kaum / Bon Kijon, Kota Malang Jawa T1mur</span>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <p>&copy; 2024 Pawarti. Semua hak cipta dilindungi.</p>
    </div>
  </footer>


  <script>
    const hamburger  = document.getElementById('hamburger');
    const navMenu    = document.getElementById('navMenu');
    const navOverlay = document.getElementById('navOverlay');
    const navbar     = document.getElementById('navbar');

    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      navMenu.classList.toggle('open');
      navOverlay.classList.toggle('show');
      document.body.style.overflow = navMenu.classList.contains('open') ? 'hidden' : '';
    });

    function closeMenu() {
      hamburger.classList.remove('open');
      navMenu.classList.remove('open');
      navOverlay.classList.remove('show');
      document.body.style.overflow = '';
    }

    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 10);
    });
  </script>
</body>
</html>