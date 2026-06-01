<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan & Statistik</title>
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

        <div class="content" id="page-laporan">
            <div class="page-header">
                <div>
                    <div class="page-title">Laporan & Statistik</div>
                    <div class="page-sub">Analisis performa konten dan keterlibatan pengguna</div>
                </div><button class="btn btn-outline"><i class="fas fa-download"></i> Ekspor Laporan</button>
            </div>
            <div class="stat-grid" id="stat-grid">
                <div class="stat-card">
                    <div class="stat-label">Pageview Bulan Ini</div>
                    <div class="stat-value" id="stat-views">187.4K</div>
                    <div class="stat-change up"><i class="fas fa-arrow-up"></i> 18% vs bulan lalu</div>
                </div>
                <div class="stat-card accent">
                    <div class="stat-label">Rata-rata Durasi</div>
                    <div class="stat-value">4m 23s</div>
                    <div class="stat-change up"><i class="fas fa-arrow-up"></i> +32 detik</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-label">Bounce Rate</div>
                    <div class="stat-value">34.2%</div>
                    <div class="stat-change up"><i class="fas fa-arrow-down"></i> Turun 5%</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-label">Pengguna Baru</div>
                    <div class="stat-value" id="stat-new-users">0</div>
                    <div class="stat-change up"><i class="fas fa-arrow-up"></i> Baru</div>
                </div>
            </div>
            <div class="grid-2">
                <div class="card">
                    <div class="card-header"><span class="card-title">Event Terlaris</span></div>
                    <div class="card-body" id="top-events">
                        <p style="color:var(--text-muted)">Memuat...</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><span class="card-title">Pesanan Terbaru</span></div>
                    <div class="card-body" id="latest-bookings">
                        <p style="color:var(--text-muted)">Memuat...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'laporan.php') {
                    link.classList.add('active');
                }
            });

            loadReports();
        });

        async function loadReports() {
            try {
                // Load users to get new users count
                const usersRes = await fetch('../../../BACKEND/admin_users.php?action=list');
                const usersData = await usersRes.json();
                if (usersData.status === 'success') {
                    document.getElementById('stat-new-users').textContent = usersData.data.length;
                }

                // Load events to show top events
                const eventsRes = await fetch('../../../BACKEND/admin_events.php?action=list');
                const eventsData = await eventsRes.json();
                if (eventsData.status === 'success') {
                    renderTopEvents(eventsData.data.slice(0, 5));
                }

                // Load bookings
                const bookingsRes = await fetch('../../../BACKEND/admin_bookings.php?action=list');
                const bookingsData = await bookingsRes.json();
                if (bookingsData.status === 'success') {
                    renderLatestBookings(bookingsData.data.slice(0, 5));
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderTopEvents(events) {
            const container = document.getElementById('top-events');
            if (events.length === 0) {
                container.innerHTML = '<p style="color:var(--text-muted)">Belum ada event</p>';
                return;
            }

            container.innerHTML = events.map((event, index) => `
        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:14px;">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <strong style="color:var(--text)">${index + 1}. ${event.judul_event}</strong>
            <span class="badge ${event.status === 'Aktif' ? 'success' : 'info'}">${event.status}</span>
          </div>
          <div style="height:8px;background:var(--border);border-radius:99px;overflow:hidden;">
            <div style="width:${Math.max(30, 100 - index * 15)}%;height:100%;background:var(--primary);border-radius:99px;"></div>
          </div>
        </div>
      `).join('');
        }

        function renderLatestBookings(bookings) {
            const container = document.getElementById('latest-bookings');
            if (bookings.length === 0) {
                container.innerHTML = '<p style="color:var(--text-muted)">Belum ada pesanan</p>';
                return;
            }

            container.innerHTML = bookings.map(booking => `
        <div class="notif-item" style="padding:10px 0;">
          <div class="notif-icon-wrap" style="background:#E8F5E9;color:#2E7D32;"><i class="fas fa-ticket-alt"></i></div>
          <div>
            <div class="notif-text">${booking.first_name || ''} ${booking.last_name || ''} memesan ${booking.judul_event}</div>
            <div class="notif-time">${new Date(booking.tanggal_booking).toLocaleDateString('id-ID')} • ${booking.jumlah_tiket} tiket</div>
          </div>
          <span class="badge ${booking.status === 'Berhasil' ? 'success' : 'warning'}">${booking.status}</span>
        </div>
      `).join('');
        }
    </script>
</body>

</html>