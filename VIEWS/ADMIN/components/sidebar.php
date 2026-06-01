<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    // Check if current path is in pages/ or not to redirect correctly
    $current_dir = dirname($_SERVER['SCRIPT_NAME']);
    if (strpos($current_dir, '/pages') !== false) {
        header("Location: login.php");
    } else {
        header("Location: pages/login.php");
    }
    exit;
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_initials = strtoupper(substr($admin_name, 0, 2));
?>
<nav class="sidebar">
    <div class="sidebar-logo">
        <span class="brand">Admin Pawerti</span>
        <span class="brand-sub">Portal Event Kebudayaan Jawa</span>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="dashboard.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="event.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-calendar-alt"></i> Event & Kegiatan</a>
        <a href="ticket.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-ticket-alt"></i> Pemesanan Tiket</a>
        <a href="kategori.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-layer-group"></i> Kategori Budaya</a>

        <div class="nav-section-label">Manajemen</div>
        <a href="user.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-users"></i> Pengguna</a>
        <a href="laporan.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-chart-bar"></i> Laporan & Statistik</a>
        <!-- <a href="media.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-photo-film"></i> Media & Galeri</a> -->
        <a href="pesan.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-envelope"></i> Pesan Masuk <span class="badge-nav">7</span></a>

        <div class="nav-section-label">Sistem</div>
        <a href="pengaturan.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-sliders-h"></i> Pengaturan</a>
        <a href="akun.php" class="nav-item" style="text-decoration: none;"><i class="fas fa-user-circle"></i> Akun Saya</a>
    </div>

    <div class="sidebar-footer">
        <div class="admin-profile" style="cursor: pointer;">
            <div class="avatar"><?php echo $admin_initials; ?></div>
            <div>
                <div class="admin-name"><?php echo htmlspecialchars($admin_name); ?></div>
                <div class="admin-role">Super Admin</div>
            </div>
            <i class="fas fa-ellipsis-v" style="margin-left:auto;color:#9B7B5A;font-size:11px;"></i>
        </div>
        <div style="margin-top:8px;">
            <div class="nav-item" style="color:#EF9A9A;cursor:pointer;" onclick="window.location.href='../../BACKEND/logout_admin.php'">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </div>
        </div>
    </div>
</nav>