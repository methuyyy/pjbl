<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginadmin.php");
    exit;
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_initials = strtoupper(substr($admin_name, 0, 2));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel — Pawerti</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --primary:       #8B2500;
  --primary-light: #C4401A;
  --primary-pale:  #FFF5F2;
  --accent:        #D4A017;
  --accent-light:  #F7D97A;
  --sidebar-bg:    #1C0D00;
  --sidebar-text:  #F5DEB3;
  --sidebar-muted: #9B7B5A;
  --sidebar-active:rgba(212,160,23,0.18);
  --sidebar-hover: rgba(255,255,255,0.06);
  --bg:            #F9F5F0;
  --card:          #FFFFFF;
  --text:          #2C1500;
  --text-muted:    #7A5C3A;
  --border:        #E8DDD0;
  --success:       #2E7D32;
  --warning:       #F57C00;
  --danger:        #C62828;
  --info:          #1565C0;
  --sidebar-w:     260px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Poppins', sans-serif;
  background: var(--bg);
  color: var(--text);
  display: flex;
  min-height: 100vh;
  overflow-x: hidden;
  font-size: 13.5px;
}

/* ── SIDEBAR ── */
.sidebar {
  width: var(--sidebar-w);
  background: var(--sidebar-bg);
  position: fixed;
  top: 0; left: 0; bottom: 0;
  display: flex;
  flex-direction: column;
  z-index: 100;
  overflow-y: auto;
}

.sidebar-logo {
  padding: 28px 24px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.07);
}
.sidebar-logo .brand {
  font-size: 17px;
  font-weight: 700;
  color: var(--accent-light);
  display: block;
}
.sidebar-logo .brand-sub {
  font-size: 10.5px;
  color: var(--sidebar-muted);
  letter-spacing: 1.5px;
  text-transform: uppercase;
  margin-top: 3px;
  display: block;
  font-weight: 400;
}

.sidebar-nav { flex: 1; padding: 16px 12px; }

.nav-section-label {
  font-size: 9.5px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--sidebar-muted);
  padding: 16px 12px 6px;
  font-weight: 600;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 10px 14px;
  border-radius: 8px;
  cursor: pointer;
  color: var(--sidebar-text);
  font-size: 13px;
  font-weight: 400;
  transition: all .2s;
  margin-bottom: 2px;
  user-select: none;
}
.nav-item i { font-size: 14px; width: 17px; text-align: center; }
.nav-item:hover { background: var(--sidebar-hover); color: #fff; }
.nav-item.active {
  background: var(--sidebar-active);
  color: var(--accent-light);
  font-weight: 500;
  border-left: 2px solid var(--accent);
}
.nav-item .badge-nav {
  margin-left: auto;
  background: var(--primary);
  color: #fff;
  font-size: 9px;
  padding: 1px 6px;
  border-radius: 99px;
  font-weight: 600;
}

.sidebar-footer {
  padding: 16px;
  border-top: 1px solid rgba(255,255,255,0.07);
}
.admin-profile {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px;
  border-radius: 8px;
  cursor: pointer;
  transition: background .2s;
}
.admin-profile:hover { background: var(--sidebar-hover); }
.avatar {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 600; color: #fff;
  flex-shrink: 0;
  font-family: 'Poppins', sans-serif;
}
.admin-name { font-size: 13px; color: var(--sidebar-text); font-weight: 500; }
.admin-role { font-size: 10.5px; color: var(--sidebar-muted); }

/* ── MAIN ── */
.main {
  margin-left: var(--sidebar-w);
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.topbar {
  height: 60px;
  background: var(--card);
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  padding: 0 28px;
  gap: 16px;
  position: sticky;
  top: 0;
  z-index: 50;
}
.topbar-title {
  font-size: 15px;
  font-weight: 600;
  color: var(--text);
  flex: 1;
}
.topbar-actions { display: flex; align-items: center; gap: 10px; }
.topbar-btn {
  width: 36px; height: 36px;
  border-radius: 50%;
  border: 1px solid var(--border);
  background: transparent;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: var(--text-muted);
  transition: all .2s;
  position: relative;
}
.topbar-btn:hover { background: var(--bg); color: var(--primary); }
.notif-dot {
  position: absolute; top: 6px; right: 6px;
  width: 7px; height: 7px;
  background: var(--primary);
  border-radius: 50%;
  border: 1.5px solid var(--card);
}
.topbar-search {
  display: flex; align-items: center; gap: 7px;
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 6px 12px;
  font-size: 12.5px;
  color: var(--text-muted);
  cursor: pointer;
}

/* ── CONTENT ── */
.content { flex: 1; padding: 28px; display: none; }
.content.active { display: block; }

.page-header {
  margin-bottom: 24px;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
}
.page-title { font-size: 22px; font-weight: 700; color: var(--text); }
.page-sub { font-size: 12px; color: var(--text-muted); margin-top: 3px; font-weight: 400; }

.btn {
  padding: 9px 18px;
  border-radius: 8px;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 500;
  cursor: pointer;
  border: none;
  transition: all .2s;
  display: inline-flex; align-items: center; justify-content: center; gap: 6px;
  text-align: center;
}
.btn-primary { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-light); }
.btn-success, .btn-warning { background: var(--primary); color: #fff; }
.btn-success:hover, .btn-warning:hover { background: var(--primary-light); }
.btn-danger { background: #C62828; color: #fff; }
.btn-danger:hover { background: #b71c1c; }
.btn-outline { background: transparent; color: var(--primary); border: 1px solid var(--primary); }
.btn-outline:hover { background: var(--primary-pale); }
.btn-sm { padding: 6px 12px; font-size: 11.5px; border-radius: 6px; }

/* Action Buttons in Table */
.btn-action {
  padding: 5px 15px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 500;
  transition: all 0.2s;
  background: #fff;
  border: 1.5px solid var(--primary);
  color: var(--primary);
  cursor: pointer;
  height: 34px;
}
.btn-action:hover { background: var(--primary); color: #fff; transform: translateY(-1px); box-shadow: 0 2px 5px rgba(139,37,0,0.2); }

/* Tombol Hapus Tetap Merah */
.btn-action.btn-danger { border-color: #C62828; color: #C62828; }
.btn-action.btn-danger:hover { background: #C62828; color: #fff; box-shadow: 0 2px 5px rgba(198,40,40,0.2); }

/* Override style success/warning jadi coklat */
.btn-action.btn-success, .btn-action.btn-warning { border-color: var(--primary); color: var(--primary); }
.btn-action.btn-success:hover, .btn-action.btn-warning:hover { background: var(--primary); color: #fff; }

.btn-action:disabled { opacity: 0.4; cursor: not-allowed; filter: grayscale(1); transform: none !important; box-shadow: none !important; }

/* Menghilangkan Icon di semua tombol */
.btn i, .btn-action i { display: none; }

/* Table Improvements */
.table-wrap { overflow-x: auto; width: 100%; }
table { width: 100%; border-collapse: collapse; min-width: 900px; }
thead th {
  text-align: left;
  padding: 12px 14px;
  font-size: 10px;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--text-muted);
  background: #FDFBFA;
  border-bottom: 1px solid var(--border);
  font-weight: 600;
  white-space: nowrap;
}
tbody td {
  padding: 14px;
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
  font-size: 12.5px;
  color: var(--text);
}
.col-aksi { white-space: nowrap; width: 1%; padding-right: 20px; }
 .col-id { width: 60px; text-align: center; }
 .col-status { width: 140px; text-align: center; }

/* ── STAT CARDS ── */
.stat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}
.stat-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px;
  position: relative;
  overflow: hidden;
}
.stat-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: var(--primary);
}
.stat-card.accent::before  { background: var(--accent); }
.stat-card.success::before { background: var(--success); }
.stat-card.info::before    { background: var(--info); }
.stat-label {
  font-size: 10px;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 8px;
  font-weight: 600;
}
.stat-value {
  font-size: 26px;
  font-weight: 700;
  color: var(--text);
  line-height: 1;
}
.stat-change { font-size: 11.5px; margin-top: 6px; }
.stat-change.up   { color: var(--success); }
.stat-change.down { color: var(--danger); }
.stat-icon {
  position: absolute; right: 16px; top: 50%;
  transform: translateY(-50%);
  font-size: 28px;
  color: var(--border);
}

/* ── CARD ── */
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 20px;
}
.card-header {
  padding: 14px 20px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.card-title { font-weight: 600; font-size: 14px; }
.card-body  { padding: 20px; }

/* ── TABLE ── */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead th {
  text-align: left;
  padding: 10px 14px;
  font-size: 10px;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--text-muted);
  background: var(--bg);
  border-bottom: 1px solid var(--border);
  font-weight: 600;
}
tbody td {
  padding: 12px 14px;
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
  font-size: 13px;
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: var(--primary-pale); }

/* ── BADGE ── */
.badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 99px;
  font-size: 10.5px;
  font-weight: 600;
}
.badge-success { background: #E8F5E9; color: #2E7D32; }
.badge-warning { background: #FFF3E0; color: #E65100; }
.badge-danger  { background: #FFEBEE; color: #C62828; }
.badge-info    { background: #E3F2FD; color: #1565C0; }
.badge-muted   { background: var(--bg); color: var(--text-muted); }

/* ── GRID ── */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
@media(max-width: 900px) {
  .grid-2, .grid-3 { grid-template-columns: 1fr; }
}

/* ── BAR CHART ── */
.bar-chart { display: flex; align-items: flex-end; gap: 8px; height: 160px; padding: 0 4px; }
.bar-item  { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
.bar {
  width: 100%;
  background: linear-gradient(to top, var(--primary), var(--primary-light));
  border-radius: 4px 4px 0 0;
  min-height: 4px;
  transition: opacity .2s;
}
.bar:hover { opacity: 0.75; }
.bar-label { font-size: 10px; color: var(--text-muted); }
.bar-val   { font-size: 10.5px; font-weight: 600; color: var(--text); }

/* ── DONUT ── */
.donut-wrap { display: flex; align-items: center; gap: 24px; }
.donut-legend { flex: 1; }
.legend-item { display: flex; align-items: center; gap: 8px; font-size: 12.5px; margin-bottom: 10px; }
.legend-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

/* ── NOTIF LIST ── */
.notif-list { list-style: none; }
.notif-item {
  display: flex;
  gap: 12px;
  padding: 13px 0;
  border-bottom: 1px solid var(--border);
  align-items: flex-start;
}
.notif-item:last-child { border-bottom: none; }
.notif-icon-wrap {
  width: 36px; height: 36px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  font-size: 14px;
}
.notif-text { font-size: 13px; color: var(--text); }
.notif-time { font-size: 11px; color: var(--text-muted); margin-top: 3px; }

/* ── CHIPS ── */
.chip-wrap { display: flex; flex-wrap: wrap; gap: 8px; }
.chip {
  padding: 5px 14px;
  border-radius: 99px;
  border: 1px solid var(--border);
  font-size: 12px;
  cursor: pointer;
  transition: all .2s;
  background: var(--card);
  font-family: 'Poppins', sans-serif;
}
.chip:hover, .chip.active {
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
}

/* ── EVENT GRID ── */
.event-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px,1fr)); gap: 16px; }
.event-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  transition: transform .2s, box-shadow .2s;
  cursor: pointer;
}
.event-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(139,37,0,0.10); }
.event-img {
  height: 140px;
  background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
  display: flex; align-items: center; justify-content: center;
  position: relative;
  overflow: hidden;
}
.event-img img { width: 100%; height: 100%; object-fit: cover; }
.event-img .overlay-label {
  position: absolute; bottom: 8px; left: 8px;
  background: rgba(0,0,0,0.55);
  color: #fff;
  font-size: 10px;
  padding: 2px 8px;
  border-radius: 99px;
  font-weight: 500;
}
/* placeholder icon inside event cards without images */
.event-icon-placeholder {
  width: 48px; height: 48px;
  background: rgba(255,255,255,0.15);
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  color: rgba(255,255,255,0.8);
}
.event-icon-placeholder svg { width: 26px; height: 26px; }
.event-body  { padding: 14px; }
.event-title { font-weight: 600; font-size: 13.5px; margin-bottom: 6px; }
.event-meta  { font-size: 11.5px; color: var(--text-muted); display: flex; gap: 12px; }

/* ── FORM ── */
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 6px; }
.form-control {
  width: 100%;
  padding: 9px 13px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  background: var(--bg);
  color: var(--text);
  transition: border .2s;
  outline: none;
}
.form-control:focus { border-color: var(--primary); background: #fff; }
select.form-control { cursor: pointer; }
textarea.form-control { resize: vertical; min-height: 90px; }

/* ── TOGGLE ── */
.toggle-wrap {
  display: flex; justify-content: space-between; align-items: center;
  padding: 14px 0;
  border-bottom: 1px solid var(--border);
}
.toggle-wrap:last-child { border-bottom: none; }
.toggle-label { font-size: 13.5px; font-weight: 500; }
.toggle-desc  { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }
.toggle {
  position: relative;
  width: 44px; height: 24px;
  background: var(--border);
  border-radius: 99px;
  cursor: pointer;
  transition: background .2s;
  flex-shrink: 0;
}
.toggle.on { background: var(--primary); }
.toggle::after {
  content: '';
  position: absolute;
  width: 18px; height: 18px;
  background: #fff;
  border-radius: 50%;
  top: 3px; left: 3px;
  transition: left .2s;
}
.toggle.on::after { left: 23px; }

/* ── UPLOAD ── */
.upload-area {
  border: 2px dashed var(--border);
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  cursor: pointer;
  transition: all .2s;
}
.upload-area:hover { border-color: var(--primary); background: var(--primary-pale); }
.upload-icon { font-size: 32px; color: var(--text-muted); margin-bottom: 10px; }
.upload-text { font-size: 13.5px; color: var(--text-muted); }

/* ── PAGINATION ── */
.pagination { display: flex; gap: 4px; align-items: center; }
.pg-btn {
  width: 32px; height: 32px;
  border: 1px solid var(--border);
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  font-size: 12.5px;
  cursor: pointer;
  background: var(--card);
  transition: all .2s;
  color: var(--text);
  font-family: 'Poppins', sans-serif;
}
.pg-btn:hover, .pg-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); }

/* ── LOGOUT MODAL ── */
#logout-modal {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.5);
  z-index: 999;
  align-items: center;
  justify-content: center;
}
.modal-box {
  background: #fff;
  border-radius: 16px;
  padding: 36px;
  max-width: 380px;
  width: 90%;
  text-align: center;
}
.modal-icon {
  width: 56px; height: 56px;
  background: var(--primary-pale);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
  color: var(--primary);
}
.modal-icon svg { width: 26px; height: 26px; }
.modal-title { font-size: 19px; font-weight: 700; margin-bottom: 8px; }
.modal-sub { font-size: 13px; color: var(--text-muted); margin-bottom: 24px; }
.modal-actions { display: flex; gap: 12px; justify-content: center; }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }

/* pw-wrap */
.pw-wrap { position: relative; }
.pw-toggle {
  position: absolute;
  right: 12px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none;
  cursor: pointer; color: var(--text-muted);
  font-size: 13px;
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<nav class="sidebar">
  <div class="sidebar-logo">
    <span class="brand">Admin Pawerti</span>
    <span class="brand-sub">Portal Event Kebudayaan Jawa</span>
  </div>

  <div class="sidebar-nav">
    <div class="nav-section-label">Utama</div>
    <div class="nav-item active" data-page="dashboard"><i class="fas fa-th-large"></i> Dashboard</div>
    <div class="nav-item" data-page="event"><i class="fas fa-calendar-alt"></i> Event & Kegiatan</div>
    <div class="nav-item" data-page="pesanan"><i class="fas fa-ticket-alt"></i> Pemesanan Tiket</div>
    <div class="nav-item" data-page="kategori"><i class="fas fa-layer-group"></i> Kategori Budaya</div>
    <div class="nav-item" data-page="pesan"><i class="fas fa-envelope"></i> Pesan Masuk</div>

    <div class="nav-section-label">Manajemen</div>
    <div class="nav-item" data-page="pengguna"><i class="fas fa-users"></i> Pengguna</div>
    <div class="nav-item" data-page="laporan"><i class="fas fa-chart-bar"></i> Laporan & Statistik</div>
    <div class="nav-item" data-page="media"><i class="fas fa-photo-film"></i> Media & Galeri</div>
    <div class="nav-item" data-page="pesan"><i class="fas fa-envelope"></i> Pesan Masuk <span class="badge-nav">7</span></div>

    <div class="nav-section-label">Sistem</div>
    <div class="nav-item" data-page="pengaturan"><i class="fas fa-sliders-h"></i> Pengaturan</div>
    <div class="nav-item" data-page="akun"><i class="fas fa-user-circle"></i> Akun Saya</div>
  </div>

  <div class="sidebar-footer">
    <div class="admin-profile" onclick="navTo('akun')">
      <div class="avatar"><?php echo $admin_initials; ?></div>
      <div>
        <div class="admin-name"><?php echo htmlspecialchars($admin_name); ?></div>
        <div class="admin-role">Super Admin</div>
      </div>
      <i class="fas fa-ellipsis-v" style="margin-left:auto;color:#9B7B5A;font-size:11px;"></i>
    </div>
    <div style="margin-top:8px;">
      <div class="nav-item" style="color:#EF9A9A;" onclick="doLogout()">
        <i class="fas fa-sign-out-alt"></i> Keluar
      </div>
    </div>
  </div>
</nav>

<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <span class="topbar-title" id="topbar-title">Dashboard</span>
    <div class="topbar-search">
      <i class="fas fa-search" style="font-size:11px;"></i> Cari sesuatu...
    </div>
    <div class="topbar-actions">
      <button class="topbar-btn" title="Notifikasi">
        <i class="fas fa-bell" style="font-size:13px;"></i>
        <span class="notif-dot"></span>
      </button>
      <button class="topbar-btn" title="Profil">
        <i class="fas fa-user-circle" style="font-size:13px;"></i>
      </button>
    </div>
  </div>

  <!-- ═══ DASHBOARD ═══ -->
  <div class="content active" id="page-dashboard">
    <div class="page-header">
      <div>
        <div class="page-title">Selamat Datang, Admin</div>
        <div class="page-sub">Ringkasan aktivitas hari ini — Senin, 18 Mei 2026</div>
      </div>
      <button class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Konten</button>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-label">Total Pengunjung</div>
        <div class="stat-value">24.830</div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> 12.4% dari bulan lalu</div>
        <div class="stat-icon"><i class="fas fa-users"></i></div>
      </div>
      <div class="stat-card accent">
        <div class="stat-label">Event Aktif</div>
        <div class="stat-value">14</div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> 3 event baru</div>
        <div class="stat-icon"><i class="fas fa-calendar"></i></div>
      </div>
      <div class="stat-card success">
        <div class="stat-label">Artikel Dipublikasi</div>
        <div class="stat-value">381</div>
        <div class="stat-change up"><i class="fas fa-arrow-up"></i> 8 artikel minggu ini</div>
        <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
      </div>
      <div class="stat-card info">
        <div class="stat-label">Pesan Masuk</div>
        <div class="stat-value">47</div>
        <div class="stat-change down"><i class="fas fa-arrow-down"></i> 7 belum dibaca</div>
        <div class="stat-icon"><i class="fas fa-envelope"></i></div>
      </div>
    </div>

    <div class="grid-2">
      <div class="card">
        <div class="card-header">
          <span class="card-title">Pengunjung 7 Hari Terakhir</span>
          <button class="btn btn-outline btn-sm">Detail</button>
        </div>
        <div class="card-body">
          <div class="bar-chart">
            <div class="bar-item"><div class="bar-val">820</div><div class="bar" style="height:55%"></div><div class="bar-label">Sen</div></div>
            <div class="bar-item"><div class="bar-val">1.2K</div><div class="bar" style="height:80%"></div><div class="bar-label">Sel</div></div>
            <div class="bar-item"><div class="bar-val">940</div><div class="bar" style="height:62%"></div><div class="bar-label">Rab</div></div>
            <div class="bar-item"><div class="bar-val">1.5K</div><div class="bar" style="height:100%"></div><div class="bar-label">Kam</div></div>
            <div class="bar-item"><div class="bar-val">1.1K</div><div class="bar" style="height:73%"></div><div class="bar-label">Jum</div></div>
            <div class="bar-item"><div class="bar-val">1.4K</div><div class="bar" style="height:93%"></div><div class="bar-label">Sab</div></div>
            <div class="bar-item"><div class="bar-val">980</div><div class="bar" style="height:65%"></div><div class="bar-label">Min</div></div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><span class="card-title">Distribusi Kategori</span></div>
        <div class="card-body">
          <div class="donut-wrap">
            <svg width="120" height="120" viewBox="0 0 120 120">
              <circle cx="60" cy="60" r="50" fill="none" stroke="#F9F5F0" stroke-width="18"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="#8B2500" stroke-width="18" stroke-dasharray="94 220" stroke-dashoffset="-55" transform="rotate(-90 60 60)"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="#D4A017" stroke-width="18" stroke-dasharray="70 244" stroke-dashoffset="-149" transform="rotate(-90 60 60)"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="#1565C0" stroke-width="18" stroke-dasharray="50 264" stroke-dashoffset="-219" transform="rotate(-90 60 60)"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="#2E7D32" stroke-width="18" stroke-dasharray="30 284" stroke-dashoffset="-269" transform="rotate(-90 60 60)"/>
              <text x="60" y="56" text-anchor="middle" font-size="9.5" fill="#7A5C3A" font-family="Poppins,sans-serif">Total</text>
              <text x="60" y="70" text-anchor="middle" font-size="14" fill="#2C1500" font-weight="700" font-family="Poppins,sans-serif">381</text>
            </svg>
            <div class="donut-legend">
              <div class="legend-item"><div class="legend-dot" style="background:#8B2500"></div> Seni Pertunjukan — 30%</div>
              <div class="legend-item"><div class="legend-dot" style="background:#D4A017"></div> Kuliner Tradisional — 22%</div>
              <div class="legend-item"><div class="legend-dot" style="background:#1565C0"></div> Kerajinan Tangan — 16%</div>
              <div class="legend-item"><div class="legend-dot" style="background:#2E7D32"></div> Wisata Budaya — 10%</div>
              <div class="legend-item"><div class="legend-dot" style="background:#E8DDD0"></div> Lainnya — 22%</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <span class="card-title">Aktivitas Terbaru</span>
        <button class="btn btn-outline btn-sm">Lihat Semua</button>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Judul Konten</th><th>Kategori</th><th>Pengguna</th><th>Waktu</th><th>Status</th></tr>
          </thead>
          <tbody>
            <tr><td><strong>Festival Reog Ponorogo 2026</strong></td><td>Event</td><td>Budi Santoso</td><td>2 jam lalu</td><td><span class="badge badge-success">Aktif</span></td></tr>
            <tr><td><strong>Resep Rawon Spesial Surabaya</strong></td><td>Kuliner</td><td>Siti Rahayu</td><td>5 jam lalu</td><td><span class="badge badge-warning">Menunggu</span></td></tr>
            <tr><td><strong>Batik Tulis Madura Modern</strong></td><td>Kerajinan</td><td>Agus Wibowo</td><td>Kemarin</td><td><span class="badge badge-success">Aktif</span></td></tr>
            <tr><td><strong>Tari Gandrung Banyuwangi</strong></td><td>Seni</td><td>Dewi Lestari</td><td>Kemarin</td><td><span class="badge badge-info">Draft</span></td></tr>
            <tr><td><strong>Wisata Gunung Bromo Terkini</strong></td><td>Wisata</td><td>Fajar Nugroho</td><td>3 hari lalu</td><td><span class="badge badge-danger">Nonaktif</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ═══ PENGGUNA ═══ -->
  <div class="content" id="page-pengguna" style="display:none">
    <div class="page-header"><div><div class="page-title">Manajemen Pengguna</div><div class="page-sub">Kelola akun, peran, dan izin pengguna</div></div></div>
    <div class="card">
      <div class="table-wrap">
        <table id="user-table">
          <thead><tr><th class="col-id">ID</th><th>Pengguna</th><th>Email</th><th>Kota</th><th>Telepon</th><th class="col-status">Status</th><th class="col-aksi">Aksi</th></tr></thead>
          <tbody id="user-list-body">
            <!-- Data akan dimuat via JS -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ═══ KATEGORI ═══ -->
  <div class="content" id="page-kategori" style="display:none">
    <div class="page-header">
      <div><div class="page-title">Manajemen Kategori</div><div class="page-sub">Kelola kategori event budaya</div></div>
      <button class="btn btn-primary" onclick="openAddCategoryModal()"><i class="fas fa-plus"></i> Tambah Kategori</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table id="category-table">
          <thead><tr><th>Nama Kategori</th><th>Icon</th><th>Deskripsi</th><th class="col-aksi">Aksi</th></tr></thead>
          <tbody id="category-list-body"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ═══ EVENT ═══ -->
  <div class="content" id="page-event" style="display:none">
    <div class="page-header">
      <div><div class="page-title">Manajemen Event</div><div class="page-sub">Kelola kegiatan dan event budaya</div></div>
      <button class="btn btn-primary" onclick="openAddEventModal()"><i class="fas fa-plus"></i> Tambah Event</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table id="event-table">
          <thead><tr><th>Event</th><th>Kategori</th><th>Tanggal</th><th>Lokasi</th><th class="col-status">Status</th><th class="col-aksi">Aksi</th></tr></thead>
          <tbody id="event-list-body"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ═══ KONTEN ═══ -->
  <div class="content" id="page-konten">
    <div class="page-header"><div><div class="page-title">Konten & Artikel</div><div class="page-sub">Buat, edit, dan kelola semua artikel serta konten budaya</div></div><button class="btn btn-primary"><i class="fas fa-plus"></i> Tulis Artikel</button></div>
    <div class="card" style="margin-bottom:20px;"><div class="card-body"><div class="chip-wrap"><div class="chip active">Semua</div><div class="chip">Dipublikasi</div><div class="chip">Draft</div><div class="chip">Menunggu Review</div><div class="chip">Seni Pertunjukan</div><div class="chip">Kuliner</div><div class="chip">Kerajinan</div><div class="chip">Wisata</div></div></div></div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>Judul Artikel</th><th>Penulis</th><th>Kategori</th><th>Tampilan</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody>
            <tr><td><strong>Sejarah dan Keindahan Reog Ponorogo</strong></td><td>Budi S.</td><td>Seni</td><td>4.821</td><td>15 Mei 2026</td><td><span class="badge badge-success">Publik</span></td><td><button class="btn btn-outline btn-sm">Edit</button></td></tr>
            <tr><td><strong>Cara Membuat Rawon Otentik</strong></td><td>Siti R.</td><td>Kuliner</td><td>3.209</td><td>12 Mei 2026</td><td><span class="badge badge-success">Publik</span></td><td><button class="btn btn-outline btn-sm">Edit</button></td></tr>
            <tr><td><strong>Batik Tulis vs Batik Cap: Perbedaannya</strong></td><td>Agus W.</td><td>Kerajinan</td><td>1.547</td><td>10 Mei 2026</td><td><span class="badge badge-warning">Review</span></td><td><button class="btn btn-outline btn-sm">Tinjau</button></td></tr>
            <tr><td><strong>10 Destinasi Wisata Budaya Jawa Timur</strong></td><td>Dewi L.</td><td>Wisata</td><td>892</td><td>8 Mei 2026</td><td><span class="badge badge-info">Draft</span></td><td><button class="btn btn-outline btn-sm">Edit</button></td></tr>
            <tr><td><strong>Gamelan: Warisan Musik Dunia dari Jawa</strong></td><td>Fajar N.</td><td>Musik</td><td>2.103</td><td>5 Mei 2026</td><td><span class="badge badge-success">Publik</span></td><td><button class="btn btn-outline btn-sm">Edit</button></td></tr>
          </tbody>
        </table>
      </div>
      <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--border);">
        <span style="font-size:12.5px;color:var(--text-muted);">Menampilkan 1–5 dari 381 artikel</span>
        <div class="pagination">
          <div class="pg-btn"><i class="fas fa-chevron-left" style="font-size:9px;"></i></div>
          <div class="pg-btn active">1</div><div class="pg-btn">2</div><div class="pg-btn">3</div><div class="pg-btn">...</div><div class="pg-btn">77</div>
          <div class="pg-btn"><i class="fas fa-chevron-right" style="font-size:9px;"></i></div>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══ KATEGORI ═══ -->
  <div class="content" id="page-kategori" style="display:none">
    <div class="page-header">
      <div>
        <div class="page-title">Manajemen Kategori</div>
        <div class="page-sub">Kelola kategori event budaya</div>
      </div>
      <button class="btn btn-primary" onclick="openAddCategoryModal()"><i class="fas fa-plus"></i> Tambah Kategori</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table id="category-table">
          <thead><tr><th>Nama Kategori</th><th>Icon</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
          <tbody id="category-list-body"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ═══ EVENT ═══ -->
  <div class="content" id="page-event" style="display:none">
    <div class="page-header">
      <div>
        <div class="page-title">Manajemen Event</div>
        <div class="page-sub">Kelola kegiatan dan event budaya</div>
      </div>
      <button class="btn btn-primary" onclick="openAddEventModal()"><i class="fas fa-plus"></i> Tambah Event</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table id="event-table">
          <thead><tr><th>Event</th><th>Kategori</th><th>Tanggal</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody id="event-list-body"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Kategori -->
  <div id="category-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="modal-box" style="max-width:500px; text-align:left;">
      <div class="modal-title" id="cat-modal-title">Tambah Kategori</div>
      <form id="category-form">
        <input type="hidden" name="id" id="cat-id">
        <div class="form-group"><label class="form-label">Nama Kategori</label><input type="text" name="nama_kategori" id="cat-nama" class="form-control" required></div>
        <div class="form-group"><label class="form-label">Icon (FontAwesome Class)</label><input type="text" name="icon" id="cat-icon" class="form-control" placeholder="fa-theater-masks"></div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" id="cat-desc" class="form-control" rows="3"></textarea></div>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeCategoryModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Event -->
  <div id="event-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="modal-box" style="max-width:600px; text-align:left;">
      <div class="modal-title" id="event-modal-title">Tambah Event</div>
      <form id="event-form" enctype="multipart/form-data">
        <input type="hidden" name="id" id="event-id">
        <div class="form-group"><label class="form-label">Judul Event</label><input type="text" name="judul_event" id="event-judul" class="form-control" required></div>
        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" id="event-cat-id" class="form-control" required></select>
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" id="event-status" class="form-control">
              <option value="Aktif">Aktif</option>
              <option value="Mendatang">Mendatang</option>
              <option value="Selesai">Selesai</option>
            </select>
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label class="form-label">Tanggal</label><input type="date" name="tanggal_event" id="event-tanggal" class="form-control"></div>
          <div class="form-group"><label class="form-label">Lokasi</label><input type="text" name="lokasi" id="event-lokasi" class="form-control"></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label class="form-label">Total Kursi</label><input type="number" name="total_kursi" id="event-total-kursi" class="form-control"></div>
          <div class="form-group"><label class="form-label">Sisa Kursi</label><input type="number" name="sisa_kursi" id="event-sisa-kursi" class="form-control"></div>
        </div>
        <div class="form-group"><label class="form-label">Harga (IDR)</label><input type="number" name="harga" id="event-harga" class="form-control" placeholder="0 untuk Gratis"></div>
        <div class="grid-2">
          <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top:10px;">
            <input type="checkbox" name="is_featured" id="event-is-featured" value="1" style="width:20px; height:20px; cursor:pointer;">
            <label class="form-label" style="margin-bottom:0; cursor:pointer;" for="event-is-featured">Jadikan Event Unggulan</label>
          </div>
          <div class="form-group">
            <label class="form-label">Sub-judul Unggulan</label>
            <input type="text" name="featured_sub" id="event-featured-sub" class="form-control" placeholder="Teks singkat penarik minat">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Gambar Event (Maks 3)</label>
          <div class="grid-3">
            <input type="file" name="gambar1" class="form-control" accept="image/*">
            <input type="file" name="gambar2" class="form-control" accept="image/*">
            <input type="file" name="gambar3" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" id="event-desc" class="form-control" rows="3"></textarea></div>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeEventModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ═══ PESAN MASUK ═══ -->
  <div class="content" id="page-pesan" style="display:none">
    <div class="page-header">
      <div>
        <div class="page-title">Pesan Masuk</div>
        <div class="page-sub">Kelola pertanyaan dan masukan dari pengguna</div>
      </div>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table id="message-table">
          <thead><tr><th>Nama</th><th>Email</th><th>Subjek</th><th>Status</th><th>Waktu</th><th>Aksi</th></tr></thead>
          <tbody id="message-list-body"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Balas Pesan -->
  <div id="reply-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="modal-box" style="max-width:600px; text-align:left;">
      <div class="modal-title">Balas Pesan</div>
      <div id="original-message" style="margin-bottom:20px; padding:15px; background:#f5f5f5; border-radius:8px; font-size:14px;"></div>
      <form id="reply-form">
        <input type="hidden" name="message_id" id="reply-msg-id">
        <div class="form-group">
          <label class="form-label">Tulis Balasan</label>
          <textarea name="balasan" id="reply-text" class="form-control" rows="5" required></textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeReplyModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Kirim Balasan</button>
        </div>
      </form>
    </div>
  </div>
  <div id="edit-user-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="modal-box" style="max-width:500px; text-align:left;">
      <div class="modal-title">Edit Pengguna</div>
      <form id="edit-user-form">
        <input type="hidden" name="id" id="edit-user-id">
        <div class="grid-2">
          <div class="form-group"><label class="form-label">Nama Depan</label><input type="text" name="first_name" id="edit-user-fn" class="form-control"></div>
          <div class="form-group"><label class="form-label">Nama Belakang</label><input type="text" name="last_name" id="edit-user-ln" class="form-control"></div>
        </div>
        <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" id="edit-user-email" class="form-control"></div>
        <div class="form-group">
          <label class="form-label">Password Baru (Kosongkan jika tidak ingin ganti)</label>
          <div class="pw-wrap">
            <input type="password" name="new_password" id="edit-user-pw" class="form-control" placeholder="Masukkan password baru">
            <button class="pw-toggle" type="button" onclick="togglePw2('edit-user-pw')"><i class="fas fa-eye"></i></button>
          </div>
        </div>
        <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="phone" id="edit-user-phone" class="form-control"></div>
        <div class="form-group"><label class="form-label">Kota</label><input type="text" name="city" id="edit-user-city" class="form-control"></div>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeEditModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ═══ LAPORAN ═══ -->
  <div class="content" id="page-laporan">
    <div class="page-header"><div><div class="page-title">Laporan & Statistik</div><div class="page-sub">Analisis performa konten dan keterlibatan pengguna</div></div><button class="btn btn-outline"><i class="fas fa-download"></i> Ekspor Laporan</button></div>
    <div class="stat-grid">
      <div class="stat-card"><div class="stat-label">Pageview Bulan Ini</div><div class="stat-value">187.4K</div><div class="stat-change up"><i class="fas fa-arrow-up"></i> 18% vs bulan lalu</div></div>
      <div class="stat-card accent"><div class="stat-label">Rata-rata Durasi</div><div class="stat-value">4m 23s</div><div class="stat-change up"><i class="fas fa-arrow-up"></i> +32 detik</div></div>
      <div class="stat-card success"><div class="stat-label">Bounce Rate</div><div class="stat-value">34.2%</div><div class="stat-change up"><i class="fas fa-arrow-down"></i> Turun 5%</div></div>
      <div class="stat-card info"><div class="stat-label">Pengguna Baru</div><div class="stat-value">2.841</div><div class="stat-change up"><i class="fas fa-arrow-up"></i> +441 bulan ini</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-header"><span class="card-title">Konten Paling Populer</span></div>
        <div class="card-body">
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;"><span>Sejarah Reog Ponorogo</span><strong>12.4K</strong></div><div style="height:8px;background:var(--border);border-radius:99px;"><div style="width:82%;height:100%;background:var(--primary);border-radius:99px;"></div></div></div>
            <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;"><span>Resep Rawon Surabaya</span><strong>9.1K</strong></div><div style="height:8px;background:var(--border);border-radius:99px;"><div style="width:60%;height:100%;background:var(--accent);border-radius:99px;"></div></div></div>
            <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;"><span>Wisata Gunung Bromo</span><strong>7.8K</strong></div><div style="height:8px;background:var(--border);border-radius:99px;"><div style="width:52%;height:100%;background:var(--info);border-radius:99px;"></div></div></div>
            <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;"><span>Batik Madura Modern</span><strong>5.3K</strong></div><div style="height:8px;background:var(--border);border-radius:99px;"><div style="width:35%;height:100%;background:var(--success);border-radius:99px;"></div></div></div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><span class="card-title">Sumber Traffic</span></div>
        <div class="card-body">
          <ul class="notif-list">
            <li class="notif-item"><div class="notif-icon-wrap" style="background:#FFF3E0;color:#E65100;"><i class="fas fa-search"></i></div><div><div class="notif-text">Pencarian Organik</div><div class="notif-time">68.4% dari total traffic</div></div><strong style="margin-left:auto;font-size:13px;">128.4K</strong></li>
            <li class="notif-item"><div class="notif-icon-wrap" style="background:#E3F2FD;color:#1565C0;"><i class="fas fa-share-alt"></i></div><div><div class="notif-text">Media Sosial</div><div class="notif-time">18.2% dari total traffic</div></div><strong style="margin-left:auto;font-size:13px;">34.2K</strong></li>
            <li class="notif-item"><div class="notif-icon-wrap" style="background:#E8F5E9;color:#2E7D32;"><i class="fas fa-link"></i></div><div><div class="notif-text">Tautan Referral</div><div class="notif-time">9.1% dari total traffic</div></div><strong style="margin-left:auto;font-size:13px;">17.1K</strong></li>
            <li class="notif-item"><div class="notif-icon-wrap" style="background:#FFEBEE;color:#C62828;"><i class="fas fa-envelope"></i></div><div><div class="notif-text">Email Newsletter</div><div class="notif-time">4.3% dari total traffic</div></div><strong style="margin-left:auto;font-size:13px;">8.1K</strong></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══ MEDIA ═══ -->
  <div class="content" id="page-media">
    <div class="page-header"><div><div class="page-title">Media & Galeri</div><div class="page-sub">Kelola foto, video, dan aset media lainnya</div></div><button class="btn btn-primary"><i class="fas fa-upload"></i> Upload Media</button></div>
    <div class="upload-area" style="margin-bottom:24px;">
      <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
      <div class="upload-text">Seret & lepas file di sini, atau <strong style="color:var(--primary);">klik untuk memilih</strong></div>
      <div style="font-size:12px;color:var(--text-muted);margin-top:6px;">Mendukung: JPG, PNG, WEBP, MP4 — Maks 50MB</div>
    </div>
    <div class="card">
      <div class="card-header"><span class="card-title">File Tersimpan</span><div style="display:flex;gap:8px;"><button class="btn btn-outline btn-sm"><i class="fas fa-th"></i></button><button class="btn btn-outline btn-sm"><i class="fas fa-list"></i></button></div></div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;">
          <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;cursor:pointer;"><div style="height:90px;background:linear-gradient(135deg,#8B2500,#D4A017);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);"><i class="fas fa-image" style="font-size:26px;"></i></div><div style="padding:8px;font-size:11px;color:var(--text-muted);">reog-ponorogo.jpg</div></div>
          <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;cursor:pointer;"><div style="height:90px;background:linear-gradient(135deg,#6A1B9A,#AB47BC);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);"><i class="fas fa-image" style="font-size:26px;"></i></div><div style="padding:8px;font-size:11px;color:var(--text-muted);">batik-madura.jpg</div></div>
          <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;cursor:pointer;"><div style="height:90px;background:linear-gradient(135deg,#E65100,#FF8F00);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);"><i class="fas fa-image" style="font-size:26px;"></i></div><div style="padding:8px;font-size:11px;color:var(--text-muted);">bromo-sunrise.jpg</div></div>
          <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;cursor:pointer;"><div style="height:90px;background:linear-gradient(135deg,#2E7D32,#66BB6A);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);"><i class="fas fa-image" style="font-size:26px;"></i></div><div style="padding:8px;font-size:11px;color:var(--text-muted);">rawon-surabaya.jpg</div></div>
          <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;cursor:pointer;"><div style="height:90px;background:linear-gradient(135deg,#1565C0,#42A5F5);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);"><i class="fas fa-video" style="font-size:26px;"></i></div><div style="padding:8px;font-size:11px;color:var(--text-muted);">gamelan-show.mp4</div></div>
          <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;cursor:pointer;"><div style="height:90px;background:linear-gradient(135deg,#00695C,#26A69A);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);"><i class="fas fa-image" style="font-size:26px;"></i></div><div style="padding:8px;font-size:11px;color:var(--text-muted);">tari-gandrung.jpg</div></div>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══ PESAN ═══ -->
  <div class="content" id="page-pesan">
    <div class="page-header"><div><div class="page-title">Pesan Masuk</div><div class="page-sub">7 pesan belum dibaca</div></div><button class="btn btn-outline"><i class="fas fa-check-double"></i> Tandai Semua Dibaca</button></div>
    <div class="grid-2">
      <div class="card">
        <div class="card-header"><span class="card-title">Daftar Pesan</span></div>
        <ul class="notif-list" style="padding:0 20px;">
          <li class="notif-item" style="cursor:pointer;background:var(--primary-pale);padding:12px 8px;border-radius:8px;margin:-4px;"><div class="notif-icon-wrap" style="background:var(--primary);color:#fff;"><i class="fas fa-user"></i></div><div style="flex:1;"><div class="notif-text" style="font-weight:600;">Budi Santoso</div><div class="notif-time">Permintaan informasi tentang Festival Reog...</div></div><div style="font-size:11px;color:var(--text-muted);flex-shrink:0;">10:30</div></li>
          <li class="notif-item" style="cursor:pointer;"><div class="notif-icon-wrap" style="background:#E3F2FD;color:#1565C0;"><i class="fas fa-user"></i></div><div style="flex:1;"><div class="notif-text">Siti Aminah</div><div class="notif-time">Terima kasih atas informasi wisata Bromo...</div></div><div style="font-size:11px;color:var(--text-muted);flex-shrink:0;">09:15</div></li>
          <li class="notif-item" style="cursor:pointer;"><div class="notif-icon-wrap" style="background:#E8F5E9;color:#2E7D32;"><i class="fas fa-building"></i></div><div style="flex:1;"><div class="notif-text">Dinas Pariwisata Jatim</div><div class="notif-time">Undangan kerjasama event bulan Juli...</div></div><div style="font-size:11px;color:var(--text-muted);flex-shrink:0;">Kemarin</div></li>
          <li class="notif-item" style="cursor:pointer;"><div class="notif-icon-wrap" style="background:#FFF3E0;color:#E65100;"><i class="fas fa-user"></i></div><div style="flex:1;"><div class="notif-text">Agus Wibowo</div><div class="notif-time">Laporan bug pada halaman artikel batik...</div></div><div style="font-size:11px;color:var(--text-muted);flex-shrink:0;">Kemarin</div></li>
        </ul>
      </div>
      <div class="card">
        <div class="card-header"><span class="card-title">Budi Santoso</span><span class="badge badge-success">Belum Dibalas</span></div>
        <div class="card-body">
          <div style="background:var(--bg);border-radius:8px;padding:14px;font-size:13px;margin-bottom:16px;"><div style="font-size:11px;color:var(--text-muted);margin-bottom:6px;">Senin, 18 Mei 2026 — 10:30</div>Halo Admin, saya ingin menanyakan informasi lebih lanjut mengenai Festival Reog Ponorogo 2026. Apakah ada paket khusus untuk rombongan?</div>
          <div class="form-group"><textarea class="form-control" placeholder="Tulis balasan..."></textarea></div>
          <button class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Balasan</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══ PEMESANAN TIKET ═══ -->
  <div class="content" id="page-pesanan">
    <div class="page-header">
      <div>
        <div class="page-title">Manajemen Pemesanan Tiket</div>
        <div class="page-sub">Kelola dan pantau seluruh pesanan tiket user</div>
      </div>
      <button class="btn btn-primary" onclick="loadBookings()"><i class="fas fa-sync"></i> Refresh Data</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th class="col-id">No</th>
              <th>No Pemesanan</th>
              <th>Event</th>
              <th>Nama User</th>
              <th>Tiket</th>
              <th>Total Harga</th>
              <th>Bukti</th>
              <th class="col-status">Status</th>
              <th>Tanggal Pesan</th>
              <th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody id="bookings-list">
            <!-- Data will be loaded via JS -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Bukti Pembayaran -->
  <div id="payment-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:2000; align-items:center; justify-content:center;">
    <div class="modal-box" style="background:#fff; padding:30px; border-radius:20px; max-width:500px; width:90%; text-align:center;">
      <h3>Bukti Pembayaran</h3>
      <div id="payment-img-container" style="margin:20px 0;">
        <img id="payment-proof-img" src="" alt="Bukti Pembayaran" style="max-width:100%; border-radius:12px; border:1px solid #eee;">
        <p id="no-payment-text" style="display:none; color:#999;">Belum ada bukti pembayaran yang diunggah.</p>
      </div>
      <div class="modal-actions">
        <button class="btn btn-outline" style="flex:1;" onclick="document.getElementById('payment-modal').style.display='none'">Tutup</button>
        <button class="btn btn-primary" id="btn-approve-payment" style="flex:1;">Aprove Pembayaran</button>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Universal (Baru) -->
  <div id="universal-confirm-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:2500; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div class="modal-box" style="background:#fff; padding:35px; border-radius:24px; max-width:420px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
      <div id="confirm-icon-wrap" style="width:70px; height:70px; background:#fff3e0; color:#f57c00; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px;">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h3 id="confirm-title" style="margin-bottom:10px; font-size:20px; font-weight:700;">Konfirmasi Tindakan</h3>
      <p id="confirm-message" style="color:#666; margin-bottom:30px; font-size:14px; line-height:1.6;">Apakah Anda yakin ingin melakukan tindakan ini?</p>
      <div class="modal-actions">
        <button class="btn btn-outline" style="flex:1; padding:12px; border-radius:50px; font-weight:600;" onclick="closeConfirmModal()">Batal</button>
        <button class="btn btn-primary" id="btn-universal-confirm" style="flex:1; padding:12px; border-radius:50px; font-weight:600;">Ya, Lanjutkan</button>
      </div>
    </div>
  </div>

  <!-- Custom Success Modal Admin -->
  <div id="admin-success-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:3000; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div class="modal-box" style="background:#fff; padding:40px; border-radius:24px; max-width:400px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
      <div style="width:80px; height:80px; background:#e8f5e9; color:#2e7d32; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:40px; margin:0 auto 20px;">
        <i class="fas fa-check"></i>
      </div>
      <h3 style="margin-bottom:10px; font-size:22px; font-weight:700;">Berhasil!</h3>
      <p id="admin-success-message" style="color:#666; margin-bottom:25px; font-size:14px;">Data telah berhasil diperbarui.</p>
      <button class="btn btn-primary" style="width:100%; padding:14px; border-radius:50px; font-weight:700;" onclick="closeAdminSuccessModal()">Oke, Lanjutkan</button>
    </div>
  </div>

  <!-- ═══ PENGATURAN ═══ -->
  <div class="content" id="page-pengaturan">
    <div class="page-header"><div><div class="page-title">Pengaturan</div><div class="page-sub">Konfigurasi sistem dan preferensi website</div></div><button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button></div>
    <div class="grid-2">
      <div>
        <div class="card" style="margin-bottom:20px;">
          <div class="card-header"><span class="card-title">Pengaturan Umum</span></div>
          <div class="card-body">
            <div class="form-group"><label class="form-label">Nama Website</label><input type="text" class="form-control" value="Jelajahi Kekayaan Budaya Jawa"></div>
            <div class="form-group"><label class="form-label">Tagline</label><input type="text" class="form-control" value="Temukan Pesona Budaya Jawa Timur"></div>
            <div class="form-group"><label class="form-label">Email Kontak</label><input type="email" class="form-control" value="info@pawerti.id"></div>
            <div class="form-group"><label class="form-label">Bahasa Default</label><select class="form-control"><option selected>Bahasa Indonesia</option><option>English</option><option>Jawa (Basa Jawi)</option></select></div>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Notifikasi</span></div>
          <div class="card-body">
            <div class="toggle-wrap"><div><div class="toggle-label">Email untuk pesan baru</div><div class="toggle-desc">Notifikasi email setiap ada pesan masuk</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
            <div class="toggle-wrap"><div><div class="toggle-label">Notifikasi event baru</div><div class="toggle-desc">Pemberitahuan ketika event baru ditambahkan</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
            <div class="toggle-wrap"><div><div class="toggle-label">Laporan mingguan</div><div class="toggle-desc">Ringkasan performa setiap Senin pagi</div></div><div class="toggle" onclick="this.classList.toggle('on')"></div></div>
            <div class="toggle-wrap"><div><div class="toggle-label">Alert keamanan</div><div class="toggle-desc">Notifikasi login dan aktivitas mencurigakan</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
          </div>
        </div>
      </div>
      <div>
        <div class="card" style="margin-bottom:20px;">
          <div class="card-header"><span class="card-title">Keamanan</span></div>
          <div class="card-body">
            <div class="form-group"><label class="form-label">Password Saat Ini</label><input type="password" class="form-control" placeholder="••••••••"></div>
            <div class="form-group"><label class="form-label">Password Baru</label><input type="password" class="form-control" placeholder="Min. 8 karakter"></div>
            <div class="form-group"><label class="form-label">Konfirmasi Password Baru</label><input type="password" class="form-control" placeholder="Ulangi password baru"></div>
            <button class="btn btn-outline" style="width:100%;">Ganti Password</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══ AKUN ═══ -->
  <div class="content" id="page-akun">
    <div class="page-header"><div><div class="page-title">Akun Saya</div><div class="page-sub">Kelola profil, keamanan, dan preferensi akun Anda</div></div><button class="btn btn-primary" onclick="saveProfile()"><i class="fas fa-save"></i> Simpan Perubahan</button></div>
    <div class="card" style="margin-bottom:20px;">
      <div class="card-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
        <div style="position:relative;">
          <div class="avatar" style="width:80px;height:80px;font-size:28px;">AS</div>
          <button style="position:absolute;bottom:0;right:0;width:26px;height:26px;background:var(--primary);border:none;border-radius:50%;cursor:pointer;color:#fff;font-size:11px;" title="Ganti foto"><i class="fas fa-camera"></i></button>
        </div>
        <div style="flex:1;">
          <div style="font-size:20px;font-weight:700;color:var(--text);">Admin Sanjaya</div>
          <div style="font-size:13px;color:var(--text-muted);margin:4px 0;">admin@nusantara.id</div>
          <div style="display:flex;gap:8px;margin-top:8px;"><span class="badge badge-danger">Super Admin</span><span class="badge badge-success">Aktif</span><span class="badge badge-muted">Bergabung Jan 2025</span></div>
        </div>
        <div style="text-align:right;"><div style="font-size:12px;color:var(--text-muted);">Terakhir login</div><div style="font-size:13px;font-weight:600;">Hari ini, 08:42</div><div style="font-size:12px;color:var(--text-muted);margin-top:4px;">dari Surabaya, ID</div></div>
      </div>
    </div>
    <div class="grid-2">
      <div>
        <div class="card" style="margin-bottom:20px;">
          <div class="card-header"><span class="card-title">Informasi Profil</span></div>
          <div class="card-body">
            <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" class="form-control" value="Admin Sanjaya"></div>
            <div class="form-group"><label class="form-label">Username</label><input type="text" class="form-control" value="adminsanjaya"></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" value="admin@nusantara.id"></div>
            <div class="form-group"><label class="form-label">Nomor Telepon</label><input type="tel" class="form-control" value="+62 812-3456-7890"></div>
            <div class="form-group"><label class="form-label">Jabatan</label><input type="text" class="form-control" value="Super Administrator"></div>
            <div class="form-group"><label class="form-label">Tentang Saya</label><textarea class="form-control">Administrator utama sistem Pawerti — mengelola konten budaya Jawa Timur sejak 2025.</textarea></div>
          </div>
        </div>
      </div>
      <div>
        <div class="card" style="margin-bottom:20px;">
          <div class="card-header"><span class="card-title">Keamanan & Password</span></div>
          <div class="card-body">
            <div class="form-group"><label class="form-label">Password Saat Ini</label><div class="pw-wrap"><input type="password" class="form-control" placeholder="••••••••" id="cur-pw"><button class="pw-toggle" type="button" onclick="togglePw2('cur-pw')"><i class="fas fa-eye"></i></button></div></div>
            <div class="form-group"><label class="form-label">Password Baru</label><div class="pw-wrap"><input type="password" class="form-control" placeholder="Min. 8 karakter" id="new-pw"><button class="pw-toggle" type="button" onclick="togglePw2('new-pw')"><i class="fas fa-eye"></i></button></div></div>
            <div class="form-group"><label class="form-label">Konfirmasi Password Baru</label><div class="pw-wrap"><input type="password" class="form-control" placeholder="Ulangi password baru" id="conf-pw"><button class="pw-toggle" type="button" onclick="togglePw2('conf-pw')"><i class="fas fa-eye"></i></button></div></div>
            <button class="btn btn-primary" style="width:100%;">Ganti Password</button>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Autentikasi 2 Faktor</span></div>
          <div class="card-body">
            <div class="toggle-wrap" style="padding-top:0;"><div><div class="toggle-label">Aktifkan 2FA</div><div class="toggle-desc">Tingkatkan keamanan dengan verifikasi tambahan</div></div><div class="toggle" onclick="this.classList.toggle('on')"></div></div>
            <div class="toggle-wrap"><div><div class="toggle-label">Verifikasi via Email</div><div class="toggle-desc">Kirim kode OTP ke admin@pawerti.id</div></div><div class="toggle on" onclick="this.classList.toggle('on')"></div></div>
            <div class="toggle-wrap"><div><div class="toggle-label">Verifikasi via SMS</div><div class="toggle-desc">Kirim kode OTP ke +62 812-xxxx-7890</div></div><div class="toggle" onclick="this.classList.toggle('on')"></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- LOGOUT MODAL -->
  <div id="logout-modal">
    <div class="modal-box">
      <div class="modal-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </div>
      <div class="modal-title">Keluar dari Admin?</div>
      <div class="modal-sub">Anda akan diarahkan kembali ke halaman login.</div>
      <div class="modal-actions">
        <button class="btn btn-outline" style="flex:1;" onclick="document.getElementById('logout-modal').style.display='none'">Batal</button>
        <button class="btn btn-primary" style="flex:1;" onclick="confirmLogout()">Ya, Keluar</button>
      </div>
    </div>
  </div>
</main>

<script>
  const pages = {
    dashboard: 'Dashboard',
    event:     'Event & Kegiatan',
    pesanan:   'Pemesanan Tiket',
    kategori:  'Kategori Budaya',
    pengguna:  'Manajemen Pengguna',
    laporan:   'Laporan & Statistik',
    media:     'Media & Galeri',
    pesan:     'Pesan Masuk',
    pengaturan:'Pengaturan',
    akun:      'Akun Saya'
  };

  function navTo(page) {
    if (page === 'pesanan') loadBookings();
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    const t = document.querySelector('[data-page="' + page + '"]');
    if (t) t.classList.add('active');
    
    // Sembunyikan semua content
    document.querySelectorAll('.content').forEach(c => {
        c.style.display = 'none';
        c.classList.remove('active');
    });

    const target = document.getElementById('page-' + page);
    if (target) {
        target.style.display = 'block';
        target.classList.add('active');
    }
    
    document.getElementById('topbar-title').textContent = pages[page] || page;

    // Load data based on page
    if (page === 'pengguna') loadUsers();
    if (page === 'kategori') loadCategories();
    if (page === 'event') loadEvents();
    if (page === 'pesan') loadMessages();
  }

  // --- Message Management JS ---
  function loadMessages() {
    fetch('../../BACKEND/admin_messages.php?action=list')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const body = document.getElementById('message-list-body');
          body.innerHTML = '';
          data.data.forEach(msg => {
            const statusBadge = msg.status === 'Menunggu' ? 'badge-warning' : 'badge-success';
            body.innerHTML += `
              <tr>
                <td>${msg.nama_lengkap}</td>
                <td>${msg.email}</td>
                <td>${msg.subjek}</td>
                <td><span class="badge ${statusBadge}">${msg.status}</span></td>
                <td>${msg.created_at}</td>
                <td>
                  <div style="display:flex;gap:6px;">
                    <button class="btn-action btn-success" onclick="replyMessage(${msg.id})" title="Balas Pesan">
                      <i class="fas fa-reply"></i> Balas
                    </button>
                    <button class="btn-action btn-danger" onclick="deleteMessage(${msg.id})" title="Hapus Pesan">
                      <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                  </div>
                </td>
              </tr>
            `;
          });
        }
      });
  }

  function replyMessage(id) {
    fetch(`../../BACKEND/admin_messages.php?action=get&id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const msg = data.data;
          document.getElementById('reply-msg-id').value = msg.id;
          document.getElementById('original-message').innerHTML = `
            <strong>Dari:</strong> ${msg.nama_lengkap} (${msg.email})<br>
            <strong>Pesan:</strong><br>${msg.pesan}
            ${msg.reply_text ? `<hr><strong>Balasan Anda sebelumnya:</strong><br>${msg.reply_text}` : ''}
          `;
          document.getElementById('reply-text').value = msg.reply_text || '';
          document.getElementById('reply-modal').style.display = 'flex';
        }
      });
  }

  function closeReplyModal() {
    document.getElementById('reply-modal').style.display = 'none';
  }

  document.getElementById('reply-form').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../../BACKEND/admin_messages.php?action=reply', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        showAdminSuccess('Balasan terkirim!');
        closeReplyModal();
        loadMessages();
      } else {
        showConfirmModal('Gagal', data.message, null, 'danger');
      }
    });
  };

  function deleteMessage(id) {
    showConfirmModal('Hapus Pesan?', 'Apakah Anda yakin ingin menghapus pesan ini?', () => {
      fetch(`../../BACKEND/admin_messages.php?action=delete&id=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            showAdminSuccess('Pesan berhasil dihapus');
            loadMessages();
          }
        });
    }, 'danger');
  }

  document.querySelectorAll('.nav-item[data-page]').forEach(item => {
    item.addEventListener('click', () => navTo(item.dataset.page));
  });

  document.querySelectorAll('.chip').forEach(chip => {
    chip.addEventListener('click', () => {
      chip.closest('.chip-wrap').querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
    });
  });

  function doLogout() {
    document.getElementById('logout-modal').style.display = 'flex';
  }

  function confirmLogout() {
    window.location.href = '../../BACKEND/logout_admin.php';
  }

  // --- CUSTOM CONFIRM MODAL ---
  let onConfirmCallback = null;
  function showConfirmModal(title, msg, callback, type = 'warning') {
    document.getElementById('confirm-title').textContent = title;
    document.getElementById('confirm-message').textContent = msg;
    const iconWrap = document.getElementById('confirm-icon-wrap');
    const confirmBtn = document.getElementById('btn-universal-confirm');
    
    if (type === 'danger') {
      iconWrap.style.background = '#ffebee';
      iconWrap.style.color = '#c62828';
      confirmBtn.style.background = '#c62828';
      confirmBtn.style.borderColor = '#c62828';
    } else {
      iconWrap.style.background = '#fff3e0';
      iconWrap.style.color = '#f57c00';
      confirmBtn.style.background = 'var(--primary)';
      confirmBtn.style.borderColor = 'var(--primary)';
    }
    
    onConfirmCallback = callback;
    document.getElementById('universal-confirm-modal').style.display = 'flex';
  }

  function closeConfirmModal() {
    document.getElementById('universal-confirm-modal').style.display = 'none';
    onConfirmCallback = null;
  }

  document.getElementById('btn-universal-confirm').onclick = function() {
    if (onConfirmCallback) onConfirmCallback();
    closeConfirmModal();
  };

  // --- CUSTOM SUCCESS MODAL ---
  function showAdminSuccess(msg) {
    document.getElementById('admin-success-message').textContent = msg;
    document.getElementById('admin-success-modal').style.display = 'flex';
  }
  function closeAdminSuccessModal() {
    document.getElementById('admin-success-modal').style.display = 'none';
  }

  // --- BOOKING MANAGEMENT JS ---
  let currentBookingId = null;

  function loadBookings() {
    fetch('../../BACKEND/admin_bookings.php?action=list')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const tbody = document.getElementById('bookings-list');
          tbody.innerHTML = '';
          data.data.forEach((b, index) => {
            const tr = document.createElement('tr');
            let badgeClass = 'badge-warning';
            if (b.status === 'Berhasil') badgeClass = 'badge-success';
            if (b.status === 'Dibatalkan') badgeClass = 'badge-danger';
            if (b.status === 'Menunggu Verifikasi') badgeClass = 'badge-info';

            const proofBtn = b.bukti_pembayaran 
              ? `<button class="btn-action" onclick="viewPayment('${b.bukti_pembayaran}', ${b.id})"><i class="far fa-image"></i> Cek</button>`
              : `<span style="color:#999;font-size:11px;">Belum Unggah</span>`;

            // Logika: Tombol aksi aktif jika sudah dicek ATAU status sudah Berhasil
            const hasChecked = viewedPayments.includes(b.id);
            const isDibatalkan = b.status === 'Dibatalkan';
            const isBerhasil = b.status === 'Berhasil';
            
            let isDisabled = '';
            let titleAttr = '';
            
            if (isDibatalkan) {
                isDisabled = 'disabled';
                titleAttr = 'Pesanan sudah dibatalkan';
            } else if (b.status === 'Menunggu Verifikasi' && !hasChecked) {
                isDisabled = 'disabled';
                titleAttr = 'Harap cek pembayaran terlebih dahulu';
            }

            tr.innerHTML = `
              <td>${index + 1}</td>
              <td><span style="font-family: monospace; font-weight: 600; color: var(--primary);">${b.no_pemesanan}</span></td>
              <td><strong>${b.judul_event}</strong></td>
              <td>${b.first_name} ${b.last_name || ''}<br><small>${b.email}</small></td>
              <td>${b.jumlah_tiket}</td>
              <td>Rp ${parseInt(b.total_harga).toLocaleString('id-ID')}</td>
              <td>${proofBtn}</td>
              <td><span class="badge ${badgeClass}">${b.status}</span></td>
              <td>${new Date(b.tanggal_booking).toLocaleDateString('id-ID')}</td>
              <td>
                <div style="display:flex;gap:6px;">
                  <button class="btn-action btn-success" onclick="updateBookingStatus(${b.id}, 'Berhasil')" ${isDisabled} title="${titleAttr || 'Approve Pesanan'}">
                    Aprove
                  </button>
                  <button class="btn-action btn-warning" onclick="updateBookingStatus(${b.id}, 'Dibatalkan')" ${isDisabled} title="${titleAttr || 'Batalkan Pesanan'}">
                    Batal
                  </button>
                  <button class="btn-action btn-danger" onclick="deleteBooking(${b.id})" title="Hapus Data Pesanan">
                    Hapus
                  </button>
                </div>
              </td>
            `;
            tbody.appendChild(tr);
          });
        }
      });
  }

  const viewedPayments = []; // Array untuk melacak pesanan yang sudah dicek

  function viewPayment(img, id) {
    if (!viewedPayments.includes(id)) viewedPayments.push(id);
    currentBookingId = id;
    const modal = document.getElementById('payment-modal');
    const proofImg = document.getElementById('payment-proof-img');
    const noText = document.getElementById('no-payment-text');
    const approveBtn = document.getElementById('btn-approve-payment');

    if (img) {
      proofImg.src = '../../images/storage/payments/' + img;
      proofImg.style.display = 'block';
      noText.style.display = 'none';
      approveBtn.style.display = 'block';
    } else {
      proofImg.style.display = 'none';
      noText.style.display = 'block';
      approveBtn.style.display = 'none';
    }
    
    approveBtn.onclick = () => {
      updateBookingStatus(currentBookingId, 'Berhasil');
      modal.style.display = 'none';
    };

    modal.style.display = 'flex';
  }

  function updateBookingStatus(id, status) {
    const title = status === 'Berhasil' ? 'Konfirmasi Pembayaran?' : 'Batalkan Pesanan?';
    const msg = status === 'Berhasil' 
      ? 'Apakah Anda yakin bukti pembayaran sudah valid dan ingin mengonfirmasi pesanan ini?' 
      : 'Apakah Anda yakin ingin membatalkan pesanan ini? Stok kursi akan dikembalikan.';
    
    showConfirmModal(title, msg, () => {
      const formData = new FormData();
      formData.append('id', id);
      formData.append('status', status);

      fetch('../../BACKEND/admin_bookings.php?action=update_status', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          showAdminSuccess(data.message);
          loadBookings();
        } else {
          showConfirmModal('Gagal', data.message, null, 'danger');
        }
      });
    }, status === 'Berhasil' ? 'warning' : 'danger');
  }

  function deleteBooking(id) {
    showConfirmModal('Hapus Pesanan?', 'Data pesanan akan dihapus permanen. Stok kursi akan dikembalikan jika status pesanan adalah Berhasil.', () => {
      fetch('../../BACKEND/admin_bookings.php?action=delete&id=' + id)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            showAdminSuccess(data.message);
            loadBookings();
          } else {
            showConfirmModal('Gagal', data.message, null, 'danger');
          }
        });
    }, 'danger');
  }

  // --- User Management JS ---
  function loadUsers() {
    fetch('../../BACKEND/admin_users.php?action=list')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const body = document.getElementById('user-list-body');
          body.innerHTML = '';
          data.data.forEach(user => {
            const avatar = user.profile_pic ? `../../images/storage/${user.profile_pic}` : null;
            const initials = user.first_name.substring(0, 2).toUpperCase();
            
            body.innerHTML += `
              <tr>
                <td>#${user.id}</td>
                <td>
                  <div style="display:flex;align-items:center;gap:10px;">
                    ${avatar ? `<img src="${avatar}" class="avatar" style="width:30px;height:30px; object-fit:cover;">` : `<div class="avatar" style="width:30px;height:30px;font-size:11px;">${initials}</div>`}
                    <strong>${user.first_name} ${user.last_name || ''}</strong>
                  </div>
                </td>
                <td>${user.email}</td>
                <td>${user.city || '-'}</td>
                <td>${user.phone || '-'}</td>
                <td><span class="badge badge-success">Aktif</span></td>
                <td>
                  <div style="display:flex;gap:6px;">
                    <button class="btn-action btn-warning" onclick="editUser(${user.id})" title="Edit User">
                      <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn-action btn-danger" onclick="deleteUser(${user.id})" title="Hapus User">
                      <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                  </div>
                </td>
              </tr>
            `;
          });
        }
      });
  }

  function editUser(id) {
    fetch(`../../BACKEND/admin_users.php?action=get&id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const user = data.data;
          document.getElementById('edit-user-id').value = user.id;
          document.getElementById('edit-user-fn').value = user.first_name;
          document.getElementById('edit-user-ln').value = user.last_name;
          document.getElementById('edit-user-email').value = user.email;
          document.getElementById('edit-user-pw').value = ''; // Selalu kosongkan saat buka modal
          document.getElementById('edit-user-phone').value = user.phone;
          document.getElementById('edit-user-city').value = user.city;
          document.getElementById('edit-user-modal').style.display = 'flex';
        }
      });
  }

  function closeEditModal() {
    document.getElementById('edit-user-modal').style.display = 'none';
  }

  document.getElementById('edit-user-form').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../../BACKEND/admin_users.php?action=update', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        showAdminSuccess('User berhasil diperbarui!');
        closeEditModal();
        loadUsers();
      } else {
        showConfirmModal('Gagal', data.message, null, 'danger');
      }
    });
  };

  function deleteUser(id) {
    showConfirmModal('Hapus User?', 'Apakah Anda yakin ingin menghapus user ini? Data tidak dapat dikembalikan.', () => {
      fetch(`../../BACKEND/admin_users.php?action=delete&id=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            showAdminSuccess('User berhasil dihapus');
            loadUsers();
          } else {
            alert(data.message);
          }
        });
    }, 'danger');
  }

  // --- Category Management JS ---
  function loadCategories() {
    fetch('../../BACKEND/admin_categories.php?action=list')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const body = document.getElementById('category-list-body');
          body.innerHTML = '';
          data.data.forEach(cat => {
            body.innerHTML += `
              <tr>
                <td><strong>${cat.nama_kategori}</strong></td>
                <td><i class="fas ${cat.icon || 'fa-tag'}"></i> ${cat.icon || '-'}</td>
                <td>${cat.deskripsi || '-'}</td>
                <td>
                  <div style="display:flex;gap:6px;">
                    <button class="btn-action btn-warning" onclick="editCategory(${cat.id})" title="Edit Kategori">
                      <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn-action btn-danger" onclick="deleteCategory(${cat.id})" title="Hapus Kategori">
                      <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                  </div>
                </td>
              </tr>
            `;
          });
        }
      });
  }

  function openAddCategoryModal() {
    document.getElementById('cat-modal-title').textContent = 'Tambah Kategori';
    document.getElementById('category-form').reset();
    document.getElementById('cat-id').value = '';
    document.getElementById('category-modal').style.display = 'flex';
  }

  function closeCategoryModal() {
    document.getElementById('category-modal').style.display = 'none';
  }

  function editCategory(id) {
    fetch(`../../BACKEND/admin_categories.php?action=get&id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const cat = data.data;
          document.getElementById('cat-modal-title').textContent = 'Edit Kategori';
          document.getElementById('cat-id').value = cat.id;
          document.getElementById('cat-nama').value = cat.nama_kategori;
          document.getElementById('cat-icon').value = cat.icon;
          document.getElementById('cat-desc').value = cat.deskripsi;
          document.getElementById('category-modal').style.display = 'flex';
        }
      });
  }

  document.getElementById('category-form').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('cat-id').value;
    const action = id ? 'update' : 'add';
    const formData = new FormData(this);
    fetch(`../../BACKEND/admin_categories.php?action=${action}`, {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        showAdminSuccess('Kategori berhasil disimpan!');
        closeCategoryModal();
        loadCategories();
      } else { 
        showConfirmModal('Gagal', data.message, null, 'danger'); 
      }
    });
  };

  function deleteCategory(id) {
    showConfirmModal('Hapus Kategori?', 'Apakah Anda yakin ingin menghapus kategori ini secara permanen?', () => {
      fetch(`../../BACKEND/admin_categories.php?action=delete&id=${id}`)
        .then(res => res.json())
        .then(data => { 
          if (data.status === 'success') {
            showAdminSuccess(data.message);
            loadCategories();
          }
        });
    }, 'danger');
  }

  // --- Event Management JS ---
  function loadEvents() {
    fetch('../../BACKEND/admin_events.php?action=list')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const body = document.getElementById('event-list-body');
          body.innerHTML = '';
          data.data.forEach(ev => {
            const img = ev.gambar1 ? `<img src="../../images/storage/${ev.gambar1}" style="width:50px;height:35px;border-radius:6px;object-fit:cover;box-shadow: 0 2px 4px rgba(0,0,0,0.1);">` : '';
            
            let statusBadge = '';
            if (ev.status === 'Aktif') statusBadge = '<span class="badge badge-success" style="background:#E8F5E9; color:#2E7D32;">Aktif</span>';
            else if (ev.status === 'Mendatang') statusBadge = '<span class="badge badge-warning" style="background:#FFF3E0; color:#E65100;">Mendatang</span>';
            else statusBadge = `<span class="badge badge-muted">${ev.status}</span>`;

            body.innerHTML += `
              <tr>
                <td>
                  <div style="display:flex;align-items:center;gap:12px;">
                    ${img}
                    <span style="font-weight:600; color:var(--text);">${ev.judul_event}</span>
                  </div>
                </td>
                <td style="color:var(--text-muted);">${ev.nama_kategori || 'Tanpa Kategori'}</td>
                <td style="color:var(--text-muted);">${ev.tanggal_event || '-'}</td>
                <td style="color:var(--text-muted);">${ev.lokasi || '-'}</td>
                <td>${statusBadge}</td>
                <td>
                  <div style="display:flex;gap:8px;">
                    <button class="btn-action" onclick="editEvent(${ev.id})" title="Edit Event" style="padding: 5px 12px; height: 30px; font-size: 11px;">Edit</button>
                    <button class="btn-action btn-danger" onclick="deleteEvent(${ev.id})" title="Hapus Event" style="padding: 5px 12px; height: 30px; font-size: 11px;">Hapus</button>
                  </div>
                </td>
              </tr>
            `;
          });
        }
      });
  }

  function openAddEventModal() {
    document.getElementById('event-modal-title').textContent = 'Tambah Event';
    document.getElementById('event-form').reset();
    document.getElementById('event-id').value = '';
    loadCategoryOptions();
    document.getElementById('event-modal').style.display = 'flex';
  }

  function closeEventModal() {
    document.getElementById('event-modal').style.display = 'none';
  }

  function loadCategoryOptions(selectedId = null) {
    fetch('../../BACKEND/admin_categories.php?action=list')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const select = document.getElementById('event-cat-id');
          select.innerHTML = '<option value="">Pilih Kategori</option>';
          data.data.forEach(cat => {
            select.innerHTML += `<option value="${cat.id}" ${cat.id == selectedId ? 'selected' : ''}>${cat.nama_kategori}</option>`;
          });
        }
      });
  }

  function editEvent(id) {
    fetch(`../../BACKEND/admin_events.php?action=get&id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          const ev = data.data;
          document.getElementById('event-modal-title').textContent = 'Edit Event';
          document.getElementById('event-id').value = ev.id;
          document.getElementById('event-judul').value = ev.judul_event;
          document.getElementById('event-tanggal').value = ev.tanggal_event;
          document.getElementById('event-lokasi').value = ev.lokasi;
          document.getElementById('event-total-kursi').value = ev.total_kursi || 0;
          document.getElementById('event-sisa-kursi').value = ev.sisa_kursi || 0;
          document.getElementById('event-harga').value = ev.harga || 0;
          document.getElementById('event-status').value = ev.status;
          document.getElementById('event-desc').value = ev.deskripsi;
          document.getElementById('event-is-featured').checked = ev.is_featured == 1;
          document.getElementById('event-featured-sub').value = ev.featured_sub || '';
          loadCategoryOptions(ev.kategori_id);
          document.getElementById('event-modal').style.display = 'flex';
        }
      });
  }

  document.getElementById('event-form').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('event-id').value;
    const action = id ? 'update' : 'add';
    const formData = new FormData(this);
    fetch(`../../BACKEND/admin_events.php?action=${action}`, {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        showAdminSuccess('Event berhasil disimpan!');
        closeEventModal();
        loadEvents();
      } else { 
        showConfirmModal('Gagal', data.message, null, 'danger'); 
      }
    });
  };

  function deleteEvent(id) {
    showConfirmModal('Hapus Event?', 'Apakah Anda yakin ingin menghapus event ini? Seluruh data terkait akan hilang.', () => {
      fetch(`../../BACKEND/admin_events.php?action=delete&id=${id}`)
        .then(res => res.json())
        .then(data => { 
          if (data.status === 'success') {
            showAdminSuccess(data.message);
            loadEvents();
          }
        });
    }, 'danger');
  }

  // Load users when page is "pengguna"
  document.querySelectorAll('.nav-item[data-page]').forEach(item => {
    item.addEventListener('click', () => navTo(item.dataset.page));
  });

  function togglePw2(id) {
    const inp = document.getElementById(id);
    if (!inp) return;
    const btn = inp.nextElementSibling;
    if (inp.type === 'password') { inp.type = 'text'; btn.innerHTML = '<i class="fas fa-eye-slash"></i>'; }
    else { inp.type = 'password'; btn.innerHTML = '<i class="fas fa-eye"></i>'; }
  }

  function saveProfile() {
    const btn = event.currentTarget;
    btn.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
    btn.style.background = 'var(--success)';
    setTimeout(() => {
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
      btn.style.background = '';
    }, 2000);
  }
</script>
</body>
</html>