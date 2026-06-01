<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';
$admin_initials = strtoupper(substr($admin_name, 0, 2));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya</title>
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

        .profile-header {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 30px;
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }

        .profile-avatar-lg {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: 700;
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
                    <div class="page-title">Akun Saya</div>
                    <div class="page-sub">Kelola informasi profil dan keamanan akun</div>
                </div>
            </div>

            <div class="profile-header">
                <div class="profile-avatar-lg"><?php echo $admin_initials; ?></div>
                <div>
                    <h2 style="margin:0 0 5px 0;"><?php echo htmlspecialchars($admin_name); ?></h2>
                    <p style="margin:0;color:var(--text-muted);"><?php echo htmlspecialchars($admin_email); ?></p>
                </div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <div class="card-header"><span class="card-title">Informasi Profil</span></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_name); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($admin_email); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" placeholder="+62">
                        </div>
                        <button class="btn btn-primary" onclick="alert('Profil berhasil diperbarui!')">Perbarui Profil</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><span class="card-title">Keamanan</span></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Lama</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Baru</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                        <button class="btn btn-primary" onclick="alert('Kata sandi berhasil diubah!')">Ubah Kata Sandi</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'akun.php') {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>