<?php
// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']);
$user_name = '';
$unread_count = 0;

if ($is_logged_in && isset($conn)) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT first_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $user_name = $user['first_name'];
    }
    $stmt->close();

    // Hitung pesan yang belum dibaca
    $stmt_unread = $conn->prepare("SELECT COUNT(*) as unread FROM messages m JOIN message_replies r ON m.id = r.message_id WHERE m.user_id = ? AND r.is_read = 0");
    $stmt_unread->bind_param("i", $user_id);
    $stmt_unread->execute();
    $unread_count = $stmt_unread->get_result()->fetch_assoc()['unread'];
    $stmt_unread->close();
}
?>

<style>
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap");

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
    font-family: 'Lato', sans-serif !important;
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
    min-width: 150px;
    display: none;
    z-index: 1000;
    margin-top: 10px;
  }
  .user-dropdown.show {
    display: block;
  }
  .user-dropdown a {
    font-family: 'Lato', sans-serif !important;
    display: block;
    padding: 8px 20px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.2s;
    text-align: left;
  }
  .user-dropdown a:hover {
    background: #f5f5f5;
    color: #8B2500;
  }

  /* Sync Navbar Style */
  body {
    padding-top: 70px; /* Offset for fixed navbar */
  }
  .navbar {
    background: white !important;
    padding: 0 !important;
    height: 70px !important;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #eee;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
  }
  .nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 60px;
  }
  .navbar-logo {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    gap: 12px;
    text-decoration: none;
    padding: 0;
    margin: 0;
  }
  .navbar-logo .logo-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .navbar-logo .logo-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
  .navbar-logo span {
    font-family: 'Playfair Display', serif !important;
    font-size: 1.4rem;
    font-weight: 700;
    color: #6b2737; /* var(--primary) */
    letter-spacing: 0.5px;
    white-space: nowrap;
  }
  .navbar-nav {
    display: flex;
    gap: 32px;
    align-items: center;
    list-style: none !important;
    margin: 0;
    padding: 0;
    font-family: 'Lato', sans-serif !important;
  }
  .navbar-nav li {
    list-style: none !important;
    font-family: 'Lato', sans-serif !important;
  }
  .navbar-nav a {
    font-family: 'Lato', sans-serif !important;
    font-size: 0.95rem;
    font-weight: 500;
    color: #555; /* var(--text-mid) */
    transition: color 0.2s;
    letter-spacing: 0.3px;
    text-decoration: none;
  }
  .navbar-nav a:hover { color: var(--primary); }
  .btn-login {
    padding: 8px 22px;
    border: 1.5px solid var(--primary);
    border-radius: 4px;
    color: var(--primary) !important;
    font-weight: 700;
    font-size: 0.88rem;
    transition: background 0.2s, color 0.2s;
  }
  .btn-login:hover {
    background: var(--primary);
    color: white !important;
  }

  /* ===== HAMBURGER ===== */
  .hamburger {
    display: none;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
    z-index: 1100;
  }
  .hamburger span {
    display: block;
    width: 24px;
    height: 2.5px;
    background: var(--primary);
    border-radius: 2px;
    transition: transform 0.3s, opacity 0.3s;
    transform-origin: center;
  }
  .hamburger.open span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
  .hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
  .hamburger.open span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

  /* ===== MOBILE OVERLAY ===== */
  .nav-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 900;
    opacity: 0;
    transition: opacity 0.3s;
  }
  .nav-overlay.show {
    display: block;
    opacity: 1;
  }

  @media (max-width: 768px) {
    .nav-container {
      padding: 0 20px;
    }
    .hamburger { display: flex; }
    .navbar-nav {
      position: fixed;
      top: 0;
      right: -280px;
      width: 260px;
      height: 100vh;
      background: white;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
      padding: 88px 32px 32px;
      gap: 8px;
      z-index: 1000;
      transition: right 0.35s cubic-bezier(.4,0,.2,1);
      box-shadow: -4px 0 24px rgba(0,0,0,0.1);
    }
    .navbar-nav.open { right: 0; }
    .navbar-nav a {
      font-size: 1rem;
      padding: 10px 0;
      border-bottom: 1px solid #eee;
      width: 100%;
      display: block;
    }
    .btn-login {
      margin-top: 8px;
      text-align: center;
      width: 100%;
    }
  }
</style>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <a href="index.php" class="navbar-logo">
      <div class="logo-icon">
        <img src="../../images/navba.png" alt="logo pawarti">
      </div>
      <span>Pawarti</span>
    </a>

    <!-- Hamburger Button (mobile) -->
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <ul class="navbar-nav" id="navMenu">
      <li><a href="index.php#hero" onclick="closeMenu()">Beranda</a></li>
      <li><a href="events.php" onclick="closeMenu()">Event</a></li>
      <li><a href="tentangkami.php" onclick="closeMenu()">Tentang Kami</a></li>
      <li><a href="index.php#kontak" onclick="closeMenu()">Kontak</a></li>
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
              <a href="bookings_user.php">Tiket Saya</a>
              <a href="messages_user.php">Pesan Saya <?php echo ($unread_count > 0) ? "($unread_count)" : ""; ?></a>
              <a href="../../BACKEND/logout.php">Keluar</a>
            </div>
          </div>
        </li>
      <?php else: ?>
        <li><a href="loginbaru.php" class="btn-login" onclick="closeMenu()">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<!-- Overlay mobile menu -->
<div class="nav-overlay" id="navOverlay" onclick="closeMenu()"></div>

<script>
  const hamburger = document.getElementById('hamburger');
  const navMenu = document.getElementById('navMenu');
  const navOverlay = document.getElementById('navOverlay');
  const navbar_el = document.getElementById('navbar');

  if (hamburger) {
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      navMenu.classList.toggle('open');
      navOverlay.classList.toggle('show');
      document.body.style.overflow = navMenu.classList.contains('open') ? 'hidden' : '';
    });
  }

  function closeMenu() {
    if (hamburger) {
      hamburger.classList.remove('open');
      navMenu.classList.remove('open');
      navOverlay.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  window.addEventListener('scroll', () => {
    if (navbar_el) {
      navbar_el.classList.toggle('scrolled', window.scrollY > 10);
    }
  });

  // Dropdown User Click
  const dropdownTrigger = document.getElementById('userDropdownTrigger');
  const dropdownMenu = document.getElementById('userDropdownMenu');

  if (dropdownTrigger) {
    dropdownTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdownMenu.classList.toggle('show');
    });

    document.addEventListener('click', () => {
      if (dropdownMenu) {
        dropdownMenu.classList.remove('show');
      }
    });
  }
</script>
