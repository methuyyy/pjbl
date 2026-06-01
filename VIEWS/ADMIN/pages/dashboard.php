<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
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
    <title>Dashboard Admin</title>
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
                    <div class="page-title">Dashboard</div>
                    <div class="page-sub">Selamat datang kembali, <?php echo htmlspecialchars($admin_name); ?></div>
                </div>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Event</div>
                    <div class="stat-value" id="stat-events">0</div>
                    <div class="stat-change up"><i class="fas fa-arrow-up"></i> Event aktif</div>
                </div>
                <div class="stat-card accent">
                    <div class="stat-label">Total Pengguna</div>
                    <div class="stat-value" id="stat-users">0</div>
                    <div class="stat-change up"><i class="fas fa-arrow-up"></i> Terdaftar</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-value" id="stat-bookings">0</div>
                    <div class="stat-change up"><i class="fas fa-arrow-up"></i> Diproses</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-label">Pesan Masuk</div>
                    <div class="stat-value" id="stat-messages">0</div>
                    <div class="stat-change up"><i class="fas fa-arrow-up"></i> Baru</div>
                </div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <div class="card-header"><span class="card-title">Event Terbaru</span></div>
                    <div class="card-body" id="recent-events">
                        <p style="color:var(--text-muted)">Memuat...</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><span class="card-title">Pesanan Terbaru</span></div>
                    <div class="card-body" id="recent-bookings">
                        <p style="color:var(--text-muted)">Memuat...</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Sukses -->
        <div id="success-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:3000; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
            <div class="modal-box" style="background:#fff; padding:40px; border-radius:24px; max-width:400px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div style="width:80px; height:80px; background:#e8f5e9; color:#2e7d32; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:40px; margin:0 auto 20px;">
                    <i class="fas fa-check"></i>
                </div>
                <h3 style="margin-bottom:10px; font-size:22px; font-weight:700;">Berhasil!</h3>
                <p id="success-message" style="color:#666; margin-bottom:25px; font-size:14px;">Data telah berhasil diperbarui.</p>
                <button class="btn btn-primary" style="width:100%; padding:14px; border-radius:50px; font-weight:700;" onclick="closeSuccessModal()">Oke, Lanjutkan</button>
            </div>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'dashboard.php') {
                    link.classList.add('active');
                }
            });

            loadDashboardData();
        });

        async function loadDashboardData() {
            try {
                // Load events
                const eventsRes = await fetch('../../../BACKEND/admin_events.php?action=list');
                const eventsData = await eventsRes.json();
                if (eventsData.status === 'success') {
                    document.getElementById('stat-events').textContent = eventsData.data.length;
                    renderRecentEvents(eventsData.data.slice(0, 5));
                }

                // Load users
                const usersRes = await fetch('../../../BACKEND/admin_users.php?action=list');
                const usersData = await usersRes.json();
                if (usersData.status === 'success') {
                    document.getElementById('stat-users').textContent = usersData.data.length;
                }

                // Load bookings
                const bookingsRes = await fetch('../../../BACKEND/admin_bookings.php?action=list');
                const bookingsData = await bookingsRes.json();
                if (bookingsData.status === 'success') {
                    document.getElementById('stat-bookings').textContent = bookingsData.data.length;
                    renderRecentBookings(bookingsData.data.slice(0, 5));
                }

                // Load messages
                const messagesRes = await fetch('../../../BACKEND/admin_messages.php?action=list');
                const messagesData = await messagesRes.json();
                if (messagesData.status === 'success') {
                    const unread = messagesData.data.filter(m => m.status !== 'Dibalas').length;
                    document.getElementById('stat-messages').textContent = unread;
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderRecentEvents(events) {
            const container = document.getElementById('recent-events');
            if (events.length === 0) {
                container.innerHTML = '<p style="color:var(--text-muted)">Belum ada event</p>';
                return;
            }

            container.innerHTML = events.map(event => `
        <div style="padding:12px 0; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
          <div>
            <strong style="color:var(--text);">${event.judul_event}</strong>
            <div style="font-size:12px; color:var(--text-muted);">${event.lokasi || '-'} • ${event.tanggal_event ? new Date(event.tanggal_event).toLocaleDateString('id-ID') : '-'}</div>
          </div>
          <span class="badge ${event.status === 'Aktif' ? 'success' : event.status === 'Mendatang' ? 'info' : 'muted'}">${event.status}</span>
        </div>
      `).join('');
        }

        function renderRecentBookings(bookings) {
            const container = document.getElementById('recent-bookings');
            if (bookings.length === 0) {
                container.innerHTML = '<p style="color:var(--text-muted)">Belum ada pesanan</p>';
                return;
            }

            container.innerHTML = bookings.map(booking => `
        <div style="padding:12px 0; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
          <div>
            <strong style="color:var(--text);">${booking.first_name || ''} ${booking.last_name || ''}</strong>
            <div style="font-size:12px; color:var(--text-muted);">${booking.judul_event || '-'} • ${booking.jumlah_tiket} tiket</div>
          </div>
          <span class="badge ${booking.status === 'Berhasil' ? 'success' : booking.status === 'Pending' ? 'warning' : 'danger'}">${booking.status}</span>
        </div>
      `).join('');
        }

        function showSuccess(message) {
            document.getElementById('success-message').textContent = message;
            document.getElementById('success-modal').style.display = 'flex';
        }

        function closeSuccessModal() {
            document.getElementById('success-modal').style.display = 'none';
        }
    </script>
</body>

</html>