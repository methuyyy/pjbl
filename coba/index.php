<?php
// ============================================================
//  index.php – Halaman Utama Pawerti
// ============================================================

require_once 'config.php';

// ---- Ambil data kategori dari database ----
$stmt_kat = $pdo->query("SELECT * FROM kategori_event ORDER BY id ASC");
$kategori_list = $stmt_kat->fetchAll();

// ---- Ambil event popular dari database ----
$stmt_ev = $pdo->query("SELECT * FROM events WHERE is_popular = 1 ORDER BY tanggal ASC LIMIT 3");
$events_list = $stmt_ev->fetchAll();

// ---- Helper: format tanggal ke Bahasa Indonesia ----
function formatTanggalID(string $tanggal): string {
    $hari_id = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bulan_id = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',
        6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',
        10=>'Oktober',11=>'November',12=>'Desember'
    ];
    $ts   = strtotime($tanggal);
    $hari = $hari_id[date('w', $ts)];
    $tgl  = date('j', $ts);
    $bln  = $bulan_id[(int)date('n', $ts)];
    $thn  = date('Y', $ts);
    return "$hari, $tgl $bln $thn";
}

// ---- Helper: format jumlah peserta ----
function formatPeserta(int $n): string {
    return number_format($n, 0, ',', '.') . ' peserta';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawerti – Jelajahi Kekayaan Budaya Jawa</title>
  <link rel="stylesheet" href="website.css" />
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar" id="navbar">
    <div class="navbar-logo">
      <div class="logo-icon">
        <img src="./images/navba.png" alt="logo pawerti" style="width:100%;height:100%;object-fit:contain;" onerror="this.style.display='none'" />
      </div>
      <span>Pawerti</span>
    </div>

    <!-- Hamburger Button (mobile) -->
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>

    <ul class="navbar-nav" id="navMenu">
      <li><a href="#" onclick="closeMenu()">Beranda</a></li>
      <li><a href="#events" onclick="closeMenu()">Event</a></li>
      <li><a href="#" onclick="closeMenu()">Tentang Kami</a></li>
      <li><a href="#kontak" onclick="closeMenu()">Kontak</a></li>
      <li><a href="#" class="btn-login" onclick="closeMenu()">Login</a></li>
    </ul>
  </nav>

  <!-- Overlay mobile menu -->
  <div class="nav-overlay" id="navOverlay" onclick="closeMenu()"></div>

  <!-- ===== HERO ===== -->
  <section class="hero">
    <div class="hero-bg"></div>
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
        <a href="#" class="btn-outline">Partisipasi</a>
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

      <?php if (empty($kategori_list)): ?>
        <p style="color:var(--text-light);grid-column:1/-1;text-align:center;">Belum ada kategori tersedia.</p>
      <?php else: ?>
        <?php foreach ($kategori_list as $kat): ?>
        <div class="category-card">
          <div class="category-icon">
            <?= $kat['icon_svg'] ?>
          </div>
          <span class="count"><?= (int)$kat['jumlah'] ?> Event</span>
          <h3><?= htmlspecialchars($kat['nama']) ?></h3>
          <p><?= htmlspecialchars($kat['deskripsi']) ?></p>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </section>

  <!-- ===== EVENT POPULAR ===== -->
  <section class="section events-section" id="events">
    <div class="section-header">
      <h2>Event Popular</h2>
      <p>Event budaya Jawa pilihan yang tak boleh dilewatkan</p>
    </div>

    <div class="events-grid">

      <?php if (empty($events_list)): ?>
        <p style="color:var(--text-light);grid-column:1/-1;text-align:center;">Belum ada event tersedia.</p>
      <?php else: ?>
        <?php foreach ($events_list as $ev): ?>
        <div class="event-card">
          <div class="event-img">
            <img src="<?= htmlspecialchars($ev['gambar_url']) ?>"
                 alt="<?= htmlspecialchars($ev['judul']) ?>"
                 onerror="this.src='https://via.placeholder.com/640x360?text=Pawerti'" />
            <span class="event-badge <?= htmlspecialchars($ev['badge_class']) ?>">
              <?= htmlspecialchars($ev['badge_label']) ?>
            </span>
            <div class="event-actions">
              <div class="icon-btn" title="Simpan">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
              </div>
              <div class="icon-btn" title="Bagikan">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
              </div>
            </div>
          </div>
          <div class="event-info">
            <h3><?= htmlspecialchars($ev['judul']) ?></h3>
            <p><?= htmlspecialchars($ev['deskripsi']) ?></p>
            <div class="event-meta">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <?= formatTanggalID($ev['tanggal']) ?>
              </span>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?= htmlspecialchars($ev['lokasi']) ?>
              </span>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <?= formatPeserta((int)$ev['jumlah_peserta']) ?>
              </span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>

    <div class="events-more">
      <a href="events.php" class="btn-more">Lihat Semua Event</a>
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
              <p>info@budayajawa.id<br/>pawerti@jawa.id</p>
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
            <tr><td>Senin – Jumat</td><td>08.00 – 17.00 WIB</td></tr>
            <tr><td>Sabtu</td><td>08.00 – 13.00 WIB</td></tr>
            <tr><td>Minggu</td><td>Tutup</td></tr>
          </table>
        </div>
      </div>

      <!-- Form Kirim Pesan -->
      <div class="contact-form">
        <h3>Kirim Pesan</h3>

        <!-- Alert box (muncul setelah submit) -->
        <div id="form-alert" style="display:none;padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:0.88rem;font-weight:600;"></div>

        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" maxlength="150" />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Masukkan email" maxlength="150" />
        </div>
        <div class="form-group">
          <label for="subjek">Subjek</label>
          <input type="text" id="subjek" name="subjek" placeholder="Masukkan subjek email" maxlength="200" />
        </div>
        <div class="form-group">
          <label for="pesan">Pesan</label>
          <textarea id="pesan" name="pesan" placeholder="Tulis pesan anda di sini..."></textarea>
        </div>
        <button class="btn-submit" id="btn-submit" onclick="kirimPesan()">Kirim pesan sekarang</button>
      </div>

    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <footer>
    <div class="footer-grid">

      <div class="footer-brand">
        <div class="brand-logo">
          <div class="logo-icon">
            <svg viewBox="0 0 24 24" fill="white"><path d="M12 2C8 2 4 6 4 10c0 5 8 12 8 12s8-7 8-12c0-4-4-8-8-8z"/></svg>
          </div>
          <span>Pawerti</span>
        </div>
        <p>Pawerti memperkenalkan kekayaan budaya Jawa melalui berbagai event seni yang inspiratif dan bermakna.</p>
      </div>

      <div class="footer-col">
        <h4>Kategori</h4>
        <ul>
          <?php foreach ($kategori_list as $kat): ?>
          <li><a href="#"><?= htmlspecialchars($kat['nama']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Kontak</h4>
        <div class="contact-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>info@pawerti.budayajawa@gmail.com</span>
        </div>
        <div class="contact-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.82 19 19.45 19.45 0 0 1 5 12.18 19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91A16 16 0 0 0 14.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <span>Telepon: 0800-5766-2459</span>
        </div>
        <div class="contact-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span>Jl. Pemuda no.22, Kaum, Bon Kijon, Kota Malang, Jawa Timur</span>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> Pawerti. Semua hak cipta dilindungi.</p>
    </div>
  </footer>


  <script>
    // ---- Navbar toggle ----
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

    // ---- Kirim form kontak via Fetch API ----
    async function kirimPesan() {
      const btn   = document.getElementById('btn-submit');
      const alert = document.getElementById('form-alert');

      const nama   = document.getElementById('nama').value.trim();
      const email  = document.getElementById('email').value.trim();
      const subjek = document.getElementById('subjek').value.trim();
      const pesan  = document.getElementById('pesan').value.trim();

      // Client-side validation ringan
      if (!nama || !email || !pesan) {
        showAlert('Nama, email, dan pesan wajib diisi.', false);
        return;
      }

      btn.disabled    = true;
      btn.textContent = 'Mengirim...';
      alert.style.display = 'none';

      const formData = new FormData();
      formData.append('nama',   nama);
      formData.append('email',  email);
      formData.append('subjek', subjek);
      formData.append('pesan',  pesan);

      try {
        const res  = await fetch('contact.php', { method: 'POST', body: formData });
        const data = await res.json();
        showAlert(data.message, data.success);

        if (data.success) {
          // Reset form
          ['nama','email','subjek','pesan'].forEach(id => document.getElementById(id).value = '');
        }
      } catch (err) {
        showAlert('Gagal mengirim pesan. Periksa koneksi internet Anda.', false);
      } finally {
        btn.disabled    = false;
        btn.textContent = 'Kirim pesan sekarang';
      }
    }

    function showAlert(msg, success) {
      const el = document.getElementById('form-alert');
      el.textContent    = msg;
      el.style.display  = 'block';
      el.style.background    = success ? '#e8f5e9' : '#fdecea';
      el.style.color         = success ? '#2e7d32' : '#c62828';
      el.style.border        = `1px solid ${success ? '#a5d6a7' : '#ef9a9a'}`;
    }
  </script>
</body>
</html>