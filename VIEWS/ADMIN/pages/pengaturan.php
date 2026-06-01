<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../components/admin-master.css">
    <link rel="stylesheet" href="../components/sidebar.css">
    <link rel="stylesheet" href="../components/topbar.css">
    <link rel="stylesheet" href="../components/admin-content.css">
    <style>
        .content {
            display: block;
        }
    </style>
</head>

<body>
    <?php include '../components/sidebar.php'; ?>

    <main class="main">
        <?php include '../components/topbar.php'; ?>

        <div class="content">
            <div class="page-header">
                <div>
                    <div class="page-title">Pengaturan</div>
                    <div class="page-sub">Kelola preferensi dan pengaturan sistem</div>
                </div>
            </div>
            <div class="grid-2">
                <div class="card">
                    <div class="card-header"><span class="card-title">Informasi Sistem</span></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Nama Website</label>
                            <input type="text" class="form-control" value="Cultural Hub">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea class="form-control" rows="3">Platform untuk menemukan dan mendaftar event budaya lokal.</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Kontak</label>
                            <input type="email" class="form-control" value="admin@culturalhub.id">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><span class="card-title">Preferensi</span></div>
                    <div class="card-body">
                        <div class="form-group" style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;">Notifikasi Email</div>
                                <div style="color:var(--text-muted);font-size:12px;">Kirim notifikasi untuk pesanan baru</div>
                            </div>
                            <div class="toggle" style="width:50px;height:26px;background:#E0E5EF;border-radius:99px;position:relative;cursor:pointer;transition:0.2s;">
                                <div class="toggle-thumb" style="position:absolute;top:3px;left:3px;width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 2px 4px rgba(0,0,0,0.1);transition:0.2s;"></div>
                            </div>
                        </div>
                        <hr style="margin:20px 0;border:1px solid var(--border);">
                        <div class="form-group" style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;">Mode Pemeliharaan</div>
                                <div style="color:var(--text-muted);font-size:12px;">Sembunyikan website dari pengunjung</div>
                            </div>
                            <div class="toggle" style="width:50px;height:26px;background:#E0E5EF;border-radius:99px;position:relative;cursor:pointer;transition:0.2s;">
                                <div class="toggle-thumb" style="position:absolute;top:3px;left:3px;width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 2px 4px rgba(0,0,0,0.1);transition:0.2s;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" onclick="alert('Pengaturan berhasil disimpan!')">Simpan Perubahan</button>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'pengaturan.php') {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>