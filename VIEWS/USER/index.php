<?php
session_start();
include '../../BACKEND/config.php';

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']);
$user_name = '';
$user_pic = '';
$user_email = '';

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT first_name, profile_pic, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $user_name = $user['first_name'];
        $user_pic = $user['profile_pic'];
        $user_email = $user['email'];
    }
    $stmt->close();

    // Hitung pesan yang belum dibaca
    $stmt_unread = $conn->prepare("SELECT COUNT(*) as unread FROM messages m JOIN message_replies r ON m.id = r.message_id WHERE m.user_id = ? AND r.is_read = 0");
    $stmt_unread->bind_param("i", $user_id);
    $stmt_unread->execute();
    $unread_count = $stmt_unread->get_result()->fetch_assoc()['unread'];
    $stmt_unread->close();
}

// Ambil data Kategori
$query_kategori = mysqli_query($conn, "SELECT * FROM kategori");
$query_event = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pawarti – Jelajahi Kekayaan Budaya Jawa</title>
  <link rel="stylesheet" href="../../CSS/website.css" />
  <style>
    .nav-user-profile {
      position: relative;
      display: flex;
      align-items: center;
      margin-left: 15px;
    }
    .user-info-wrapper {
      display: flex;
      align-items: center;
      gap: 5px;
      cursor: pointer;
      padding: 5px 12px;
      border-radius: 50px;
      transition: background 0.3s;
      position: relative;
    }
    .user-info-wrapper:hover {
      background: rgba(0,0,0,0.05);
    }
    .nav-user-name {
      font-weight: 500;
      color: #333;
      font-size: 14px;
      position: relative;
    }
    .notification-badge {
      background: #ff4d4d;
      color: white;
      font-size: 10px;
      font-weight: 700;
      min-width: 18px;
      height: 18px;
      padding: 0 4px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      top: -8px;
      right: -10px;
      border: 2px solid white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      line-height: 1;
    }
    .user-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      background: white;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border-radius: 8px;
      padding: 10px 0;
      min-width: 120px;
      display: none;
      z-index: 1000;
      margin-top: 10px;
    }
    .user-dropdown.show {
      display: block;
    }
    .user-dropdown a {
      display: block;
      padding: 8px 20px;
      color: #333;
      text-decoration: none;
      font-size: 14px;
      transition: background 0.2s;
    }
    .user-dropdown a:hover {
      background: #f5f5f5;
      color: #8B2500;
    }
  </style>
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar" id="navbar">
    <div class="navbar-logo">
      <div class="logo-icon">
       <img src="../../images/navba.png" alt="logo pawarti">
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
      <?php if ($is_logged_in): ?>
        <li class="nav-user-profile">
          <div class="user-info-wrapper" id="userDropdownTrigger">
            <span class="nav-user-name">
              Halo, <?php echo htmlspecialchars($user_name); ?>
              <?php if ($unread_count > 0): ?>
                <span class="notification-badge"><?php echo $unread_count; ?></span>
              <?php endif; ?>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; opacity: 0.7;"><path d="m6 9 6 6 6-6"/></svg>
            <div class="user-dropdown" id="userDropdownMenu">
              <a href="messages_user.php">Pesan Saya <?php echo ($unread_count > 0) ? "($unread_count)" : ""; ?></a>
              <a href="../../BACKEND/logout.php">Keluar</a>
            </div>
          </div>
        </li>
      <?php else: ?>
        <li><a href="../USER/loginbaru.php" class="btn-login" onclick="closeMenu()">Login</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- Overlay mobile menu -->
  <div class="nav-overlay" id="navOverlay" onclick="closeMenu()"></div>

  <!-- ===== HERO ===== -->
  <section class="hero">
    <div class="hero-bg"><img src="../../images/framehero.png" alt="Hero Background" width="1900" height="500"></div>
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
      <?php while($cat = mysqli_fetch_assoc($query_kategori)): ?>
        <div class="category-card">
          <div class="category-icon">
            <i class="fas <?php echo htmlspecialchars($cat['icon'] ?: 'fa-tag'); ?>" style="font-size: 24px; color: var(--primary);"></i>
          </div>
          <h3><?php echo htmlspecialchars($cat['nama_kategori']); ?></h3>
          <p><?php echo htmlspecialchars($cat['deskripsi']); ?></p>
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
      <?php while($ev = mysqli_fetch_assoc($query_event)): ?>
        <div class="event-card">
          <div class="event-img">
            <?php if($ev['gambar']): ?>
              <img src="../../images/storage/<?php echo $ev['gambar']; ?>" alt="<?php echo htmlspecialchars($ev['judul_event']); ?>" />
            <?php else: ?>
              <img src="../../images/reogponorogo.png" alt="Default Event Image" />
            <?php endif; ?>
            <span class="event-badge"><?php echo htmlspecialchars($ev['status']); ?></span>
          </div>
          <div class="event-info">
            <h3><?php echo htmlspecialchars($ev['judul_event']); ?></h3>
            <p><?php echo htmlspecialchars(substr($ev['deskripsi'], 0, 150)) . '...'; ?></p>
            <div class="event-meta">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <?php echo date('d M Y', strtotime($ev['tanggal_event'])); ?>
              </span>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo htmlspecialchars($ev['lokasi']); ?>
              </span>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
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
  <footer>
    <div class="footer-grid">

      <div class="footer-brand">
        <div class="brand-logo">
          <div class="logo-icon">
            <img src="../../images/logobg.png" alt="Logo Pawarti">
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

    // Dropdown User Click
    const dropdownTrigger = document.getElementById('userDropdownTrigger');
    const dropdownMenu    = document.getElementById('userDropdownMenu');

    if (dropdownTrigger) {
      dropdownTrigger.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
      });

      document.addEventListener('click', () => {
        dropdownMenu.classList.remove('show');
      });
    }

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
          alert(data.message);
          if (data.status === 'success') {
            contactForm.reset();
          }
        })
        .catch(err => {
          btn.disabled = false;
          btn.textContent = 'Kirim pesan sekarang';
          alert('Terjadi kesalahan koneksi.');
        });
      });
    }
  </script>
</body>
</html>