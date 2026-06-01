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
    <title>Kelola Pemesanan Tiket - Admin</title>
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

        <div class="content" id="page-pesanan">
            <div class="page-header">
                <div>
                    <div class="page-title">Kelola Pemesanan</div>
                    <div class="page-sub">Kelola semua pemesanan tiket event</div>
                </div>
            </div>
            <div class="card">
                <div class="table-wrap">
                    <table id="booking-table">
                        <thead>
                            <tr>
                                <th>ID Pemesanan</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Jumlah Tiket</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Bukti Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="booking-list-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Ubah Status -->
        <div id="status-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:2000; align-items:center; justify-content:center;">
            <div class="modal-box" style="background:#fff; border-radius:24px; padding:35px; max-width:450px; width:90%;">
                <div class="modal-title">Ubah Status Pemesanan</div>
                <form id="status-form">
                    <input type="hidden" name="id" id="booking-id">
                    <div class="form-group" style="margin-top:20px;">
                        <label class="form-label">Status Baru</label>
                        <select name="status" id="status-select" class="form-control">
                            <option value="Pending">Pending</option>
                            <option value="Berhasil">Berhasil</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="modal-actions" style="display:flex; gap:12px; margin-top:24px;">
                        <button type="button" class="btn btn-outline" onclick="closeStatusModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div id="confirm-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:2500; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
            <div class="modal-box" style="background:#fff; padding:35px; border-radius:24px; max-width:420px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div id="confirm-icon-wrap" style="width:70px; height:70px; background:#fff3e0; color:#f57c00; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 id="confirm-title" style="margin-bottom:10px; font-size:20px; font-weight:700;">Konfirmasi Hapus</h3>
                <p id="confirm-message" style="color:#666; margin-bottom:30px; font-size:14px; line-height:1.6;">Apakah Anda yakin ingin menghapus pemesanan ini?</p>
                <div class="modal-actions" style="display: flex; gap:12px;">
                    <button class="btn btn-outline" style="flex:1; padding:12px; border-radius:50px; font-weight:600;" onclick="closeConfirmModal()">Batal</button>
                    <button class="btn btn-danger" id="btn-confirm" style="flex:1; padding:12px; border-radius:50px; font-weight:600;">Ya, Hapus</button>
                </div>
            </div>
        </div>

        <!-- Modal Bukti Pembayaran -->
        <div id="bukti-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:3500; align-items:center; justify-content:center; padding:20px;">
            <div style="background:#fff; border-radius:24px; max-width:700px; width:100%; max-height:90vh; overflow:auto;">
                <div style="padding:20px 25px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border);">
                    <div style="font-size:18px; font-weight:700;">Bukti Pembayaran</div>
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeBuktiModal()">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
                <div style="padding:25px;">
                    <img id="bukti-image" src="" alt="Bukti Pembayaran" style="width:100%; border-radius:12px;">
                    <div id="mark-checked-btn" style="margin-top:20px; display:flex; gap:12px;">
                        <button class="btn btn-primary" style="flex:1;" onclick="markAsChecked()">Tandai Sudah Diperiksa</button>
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
        // Load checked bookings from localStorage
        function getCheckedBookings() {
            const saved = localStorage.getItem('checkedBookings');
            return saved ? new Set(JSON.parse(saved)) : new Set();
        }

        function saveCheckedBookings(checkedSet) {
            localStorage.setItem('checkedBookings', JSON.stringify(Array.from(checkedSet)));
        }

        let checkedBookings = getCheckedBookings();
        let currentBookingId = null;
        let currentBookingProofPath = null;

        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'ticket.php') {
                    link.classList.add('active');
                }
            });
            loadBookings();
        });

        async function loadBookings() {
            try {
                const res = await fetch('../../../BACKEND/admin_bookings.php?action=list');
                const data = await res.json();
                if (data.status === 'success') {
                    renderBookings(data.data);
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderBookings(bookings) {
            const tbody = document.getElementById('booking-list-body');
            tbody.innerHTML = bookings.map(booking => {
                const statusClass = booking.status === 'Berhasil' ? 'success' : booking.status === 'Pending' || booking.status === 'Belum Bayar' ? 'warning' : booking.status === 'Menunggu Verifikasi' ? 'warning' : 'danger';
                const fullName = `${booking.first_name || ''} ${booking.last_name || ''}`.trim();
                const hasProof = !!booking.bukti_pembayaran;
                const isChecked = checkedBookings.has(Number(booking.id));
                const disableActions = hasProof && !isChecked;

                return `
          <tr data-booking-id="${booking.id}">
            <td>#${booking.id}</td>
            <td>${fullName} <br><span style="font-size:12px;color:var(--text-muted);">${booking.email}</span></td>
            <td>${booking.judul_event}</td>
            <td>${booking.jumlah_tiket}</td>
            <td>Rp ${parseInt(booking.total_harga || 0).toLocaleString('id-ID')}</td>
            <td><span class="badge ${statusClass}">${booking.status}</span></td>
            <td>${new Date(booking.tanggal_booking).toLocaleDateString('id-ID')}</td>
            <td>
              ${hasProof ? `
                <button class="btn btn-primary btn-sm" onclick="openBuktiModal(${booking.id}, '${booking.bukti_pembayaran}')">
                  <i class="fas fa-eye"></i> Lihat Bukti
                </button>
                ${isChecked ? `<br><span style="font-size:12px;color:var(--success);"><i class="fas fa-check-circle"></i> Sudah diperiksa</span>` : ''}
              ` : '<span style="color:var(--text-muted);font-size:13px;">Belum upload</span>'}
            </td>
            <td class="col-aksi">
              <button class="btn btn-outline btn-sm" onclick="openStatusModal(${booking.id}, '${booking.status}')" ${disableActions ? 'disabled' : ''}>Ubah Status</button>
              <button class="btn btn-danger btn-sm" onclick="confirmDeleteBooking(${booking.id})" ${disableActions ? 'disabled' : ''}>Hapus</button>
            </td>
          </tr>`;
            }).join('');
        }

        function openBuktiModal(id, buktiPath) {
            currentBookingId = id;
            currentBookingProofPath = buktiPath;

            // Handle image path
            let fullPath;
            if (buktiPath) {
                if (buktiPath.startsWith('uploads/') || buktiPath.startsWith('images/')) {
                    fullPath = `../../../${buktiPath}`;
                } else {
                    fullPath = `../../../images/storage/payments/${buktiPath}`;
                }
            }

            document.getElementById('bukti-image').src = fullPath;

            // Show/hide mark as checked button
            const isChecked = checkedBookings.has(Number(id));
            const markBtn = document.getElementById('mark-checked-btn');
            if (isChecked) {
                markBtn.style.display = 'none';
            } else {
                markBtn.style.display = 'flex';
            }

            document.getElementById('bukti-modal').style.display = 'flex';
        }

        function closeBuktiModal() {
            document.getElementById('bukti-modal').style.display = 'none';
            document.getElementById('bukti-image').src = '';
            currentBookingId = null;
            currentBookingProofPath = null;
        }

        async function markAsChecked() {
            if (!currentBookingId) return;

            try {
                // Call backend to update status to Berhasil
                const formData = new FormData();
                formData.append('id', currentBookingId);
                formData.append('status', 'Berhasil');

                const res = await fetch('../../../BACKEND/admin_bookings.php?action=update_status', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.status === 'success') {
                    // Mark as checked in localStorage
                    checkedBookings.add(Number(currentBookingId));
                    saveCheckedBookings(checkedBookings);

                    closeBuktiModal();
                    showSuccess('Bukti pembayaran telah diperiksa dan status diubah menjadi Berhasil');
                    loadBookings();
                } else {
                    alert('Gagal memperbarui status: ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat memproses');
            }
        }

        function openStatusModal(id, currentStatus) {
            document.getElementById('booking-id').value = id;
            document.getElementById('status-select').value = currentStatus;
            document.getElementById('status-modal').style.display = 'flex';
        }

        function closeStatusModal() {
            document.getElementById('status-modal').style.display = 'none';
        }

        document.getElementById('status-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const res = await fetch('../../../BACKEND/admin_bookings.php?action=update_status', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    closeStatusModal();
                    showSuccess('Status berhasil diperbarui');
                    loadBookings();
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan');
            }
        });

        let pendingDeleteId = null;

        function confirmDeleteBooking(id) {
            pendingDeleteId = id;
            document.getElementById('confirm-modal').style.display = 'flex';
            document.getElementById('btn-confirm').onclick = deleteBooking;
        }

        async function deleteBooking() {
            if (!pendingDeleteId) return;
            try {
                const res = await fetch(`../../../BACKEND/admin_bookings.php?action=delete&id=${pendingDeleteId}`);
                const data = await res.json();
                if (data.status === 'success') {
                    closeConfirmModal();
                    showSuccess('Pemesanan berhasil dihapus');
                    loadBookings();
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan');
            }
            pendingDeleteId = null;
        }

        function closeConfirmModal() {
            document.getElementById('confirm-modal').style.display = 'none';
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