<?php
session_start();
include '../../BACKEND/config.php';

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']);
$user_name = '';
$user_pic = '';
$user_email = '';

// Ambil data Kategori dengan hitungan event
$query_kategori = mysqli_query($conn, "SELECT k.*, (SELECT COUNT(*) FROM events e WHERE e.kategori_id = k.id) as total_events FROM kategori k");
$query_event = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawarti – Jelajahi Kekayaan Budaya Jawa</title>
  <link rel="stylesheet" href="../../CSS/website.css" />
  <!-- Font Awesome 6.5.1 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    /* Modern Card Design sync with events.php */
    .events-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 25px;
      padding: 40px 60px;
      /* Ditambah padding kiri kanan agar center */
      max-width: 1400px;
      margin: 0 auto;
      justify-content: center;
      /* Memastikan grid items di tengah jika kurang dari full width */
    }

    .modern-card {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      aspect-ratio: 3/4;
      background: #000;
      color: #fff;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease;
      text-align: left;
      width: 100%;
      /* Memastikan lebar penuh di dalam grid cell */
    }

    .modern-card:hover {
      transform: translateY(-10px);
    }

    .card-slider {
      position: absolute;
      inset: 0;
      display: flex;
      transition: transform 0.5s ease;
      width: 100%;
      height: 100%;
    }

    .slider-img {
      min-width: 100%;
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* Poster tetap proporsional dan memenuhi kotak */
      opacity: 0.7;
      display: block;
    }

    .card-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.1) 100%);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 24px;
      pointer-events: none;
      /* Klik tembus ke card jika diperlukan */
    }

    .card-overlay * {
      pointer-events: auto;
    }

    /* Aktifkan kembali klik untuk konten di dalamnya */

    .slider-dots {
      position: absolute;
      bottom: 180px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 6px;
      z-index: 5;
    }

    .dot {
      width: 6px;
      height: 6px;
      background: rgba(255, 255, 255, 0.4);
      border-radius: 50%;
      transition: all 0.3s;
    }

    .dot.active {
      background: #fff;
      width: 18px;
      border-radius: 10px;
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 8px;
      color: #fff;
    }

    .card-location {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 20px;
    }

    .card-info-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
      padding: 12px 0;
      margin-bottom: 24px;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.85rem;
    }

    .info-item i {
      opacity: 0.7;
      font-size: 0.9rem;
    }

    .card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-price {
      background: rgba(255, 255, 255, 0.15);
      padding: 10px 20px;
      border-radius: 50px;
      font-weight: 600;
      backdrop-filter: blur(10px);
    }

    .btn-reserve {
      background: #fff;
      color: #000;
      padding: 12px 24px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.95rem;
      flex: 1;
      text-align: center;
      margin-left: 15px;
      transition: background 0.3s;
    }

    .btn-reserve:hover {
      background: #f0f0f0;
    }

    /* Hero Section Responsiveness */
    .hero {
      position: relative;
      width: 100%;
      height: 600px;
      /* Sesuaikan tinggi hero */
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .hero-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }

    .hero-bg img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* Gambar memenuhi layar tanpa gepeng */
      object-position: center;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.2);
      /* Overlay agar teks terbaca */
      z-index: 2;
    }

    .hero-content {
      position: relative;
      z-index: 3;
      text-align: center;
      color: white;
      max-width: 800px;
      padding: 0 20px;
    }

    @media (max-width: 768px) {
      .hero {
        height: 500px;
      }

      .events-grid {
        padding: 20px;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      }

      .nav-container {
        padding: 0 20px;
      }
    }
  </style>
</head>

<body>

  <!-- ===== NAVBAR ===== -->
  <?php include '../COMPONENTS/navbar.php'; ?>
  <?php include '../COMPONENTS/user_modals.php'; ?>

  <!-- ===== HERO ===== -->
  <section class="hero">
    <div class="hero-bg"><img src="../../images/framehero.png" alt="Hero Background"></div>
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
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
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
      <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) : ?>
        <div class="category-card">
          <div class="category-icon">
            <?php
            $icon_class = $kategori['icon'];
            // Jika tidak ada 'fa' di dalam string, tambahkan 'fas' sebagai default
            if (strpos($icon_class, 'fa') === false) {
              $icon_class = "fas " . $icon_class;
            } else if (strpos($icon_class, 'fa-') !== false && strpos($icon_class, 'fa ') === false && strpos($icon_class, 'fas') === false && strpos($icon_class, 'fab') === false && strpos($icon_class, 'far') === false) {
              // Jika ada fa- tapi tidak ada prefix fas/fab/far/fa
              $icon_class = "fas " . $icon_class;
            }
            ?>
            <i class="<?php echo $icon_class; ?>"></i>
          </div>
          <h3><?php echo htmlspecialchars($kategori['nama_kategori']); ?></h3>
          <div class="category-badge">
            <?php echo $kategori['total_events']; ?> Event
          </div>
          <p><?php echo htmlspecialchars($kategori['deskripsi']); ?></p>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- ===== EVENT POPULAR ===== -->
  <section class="section events-section" id="events">
    <div class="section-header">
      <h2>Event Popular</h2>
      <p>Event budaya Jawa pilihan yang tak boleh dilewatkan</p>
    </div>

    <div class="events-grid">
      <?php while ($event = mysqli_fetch_assoc($query_event)) : ?>
        <div class="modern-card">
          <div class="card-slider" id="slider-<?php echo $event['id']; ?>">
            <?php
            $gambar1 = $event['gambar1'] ?: $event['gambar'] ?: 'default.png';
            $gambar2 = $event['gambar2'] ?? '';
            $gambar3 = $event['gambar3'] ?? '';
            $img1 = (strpos($gambar1, 'uploads/') !== false || strpos($gambar1, 'images/') !== false) ? $gambar1 : 'images/storage/' . $gambar1;
            $img2 = (strpos($gambar2, 'uploads/') !== false || strpos($gambar2, 'images/') !== false) ? $gambar2 : 'images/storage/' . $gambar2;
            $img3 = (strpos($gambar3, 'uploads/') !== false || strpos($gambar3, 'images/') !== false) ? $gambar3 : 'images/storage/' . $gambar3;
            ?>
            <img src="../../<?php echo $img1; ?>" class="slider-img" alt="">
            <?php if ($gambar2): ?>
              <img src="../../<?php echo $img2; ?>" class="slider-img" alt="">
            <?php endif; ?>
            <?php if ($gambar3): ?>
              <img src="../../<?php echo $img3; ?>" class="slider-img" alt="">
            <?php endif; ?>
          </div>

          <div class="slider-dots">
            <div class="dot active"></div>
            <?php if (isset($event['gambar2']) && $event['gambar2']): ?><div class="dot"></div><?php endif; ?>
            <?php if (isset($event['gambar3']) && $event['gambar3']): ?><div class="dot"></div><?php endif; ?>
          </div>

          <div class="card-overlay">
            <h2 class="card-title"><?php echo htmlspecialchars($event['judul_event']); ?></h2>
            <div class="card-location">
              <i class="fas fa-map-marker-alt"></i>
              <?php echo htmlspecialchars($event['lokasi']); ?>
            </div>

            <div class="card-info-row">
              <div class="info-item">
                <i class="fas fa-chair"></i>
                <span>Total: <?php echo isset($event['total_kursi']) ? $event['total_kursi'] : '0'; ?></span>
              </div>
              <div class="info-item">
                <i class="fas fa-user-clock"></i>
                <span>Sisa: <?php echo isset($event['sisa_kursi']) ? $event['sisa_kursi'] : '0'; ?></span>
              </div>
              <div class="info-item">
                <i class="fas fa-calendar-day"></i>
                <span><?php echo date('d M', strtotime($event['tanggal_event'])); ?></span>
              </div>
            </div>

            <div class="card-footer">
              <div class="card-price">
                <?php echo ($event['harga'] > 0) ? 'Rp ' . number_format($event['harga'], 0, ',', '.') : 'Gratis'; ?>
              </div>
              <a href="events.php" class="btn-reserve">Pesan Sekarang</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    </div>

    <div class="events-more">
      <a href="events.php" class="btn-more" style="display:inline-block;text-decoration:none;">Lihat Semua Event</a>
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
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                <polyline points="22,6 12,13 2,6" />
              </svg>
            </div>
            <div class="contact-item-text">
              <p>Email</p>
              <p>info@budayajawa.id<br />Email: pawarti@jawa.id</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.82 19 19.45 19.45 0 0 1 5 12.18 19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91A16 16 0 0 0 14.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
              </svg>
            </div>
            <div class="contact-item-text">
              <p>Telepon</p>
              <p>0800-5766-2459<br />0812-7540-3241</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z" />
                <circle cx="12" cy="10" r="3" />
              </svg>
            </div>
            <div class="contact-item-text">
              <p>Alamat</p>
              <p>Jl. Pemuda no.22, Kaum, Bon Kijon,<br />Kota Malang, Jawa Timur</p>
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
        <form id="contactForm">
          <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required value="<?php echo $is_logged_in ? htmlspecialchars($user_name) : ''; ?>" />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email" required value="<?php echo $is_logged_in ? htmlspecialchars($user_email) : ''; ?>" />
          </div>
          <div class="form-group">
            <label for="subjek">Subjek</label>
            <input type="text" id="subjek" name="subjek" placeholder="Masukkan subjek email" required />
          </div>
          <div class="form-group">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan" placeholder="Tulis pesan anda di sini..." required></textarea>
          </div>
          <button type="submit" class="btn-submit" id="btnSendMsg">Kirim pesan sekarang</button>
        </form>
      </div>

    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <?php include '../COMPONENTS/footer.php'; ?>


  <script>
    // Simple Slider Logic for Modern Cards
    document.querySelectorAll('.modern-card').forEach(card => {
      const slider = card.querySelector('.card-slider');
      const dots = card.querySelectorAll('.dot');
      let currentIdx = 0;
      const totalImgs = slider.querySelectorAll('.slider-img').length;

      if (totalImgs > 1) {
        setInterval(() => {
          currentIdx = (currentIdx + 1) % totalImgs;
          slider.style.transform = `translateX(-${currentIdx * 100}%)`;
          dots.forEach((dot, idx) => {
            dot.classList.toggle('active', idx === currentIdx);
          });
        }, 3000);
      }
    });

    // Contact Form & Replies Logic
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
      contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const btn = document.getElementById('btnSendMsg');
        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        const formData = new FormData(contactForm);
        fetch('../../BACKEND/messages.php', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            btn.disabled = false;
            btn.textContent = 'Kirim pesan sekarang';
            if (data.status === 'success') {
              showAlert(data.message, 'Pesan Terkirim', 'success');
              contactForm.reset();
            } else {
              showAlert(data.message, 'Gagal Mengirim', 'error');
            }
          })
          .catch(err => {
            btn.disabled = false;
            btn.textContent = 'Kirim pesan sekarang';
            showAlert('Terjadi kesalahan koneksi.', 'Error', 'error');
          });
      });
    }
  </script>
</body>

</html>