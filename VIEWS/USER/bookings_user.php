<?php
session_start();
include '../../BACKEND/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginbaru.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data booking user
$query_bookings = mysqli_query($conn, "
    SELECT b.*, e.judul_event, e.tanggal_event, e.lokasi, e.gambar1 
    FROM bookings b 
    JOIN events e ON b.event_id = e.id 
    WHERE b.user_id = $user_id 
    ORDER BY b.tanggal_booking DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya — Pawerti</title>
    <link rel="stylesheet" href="../../CSS/website.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .bookings-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            min-height: 60vh;
        }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #6b2737;
        }
        .booking-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            overflow: hidden;
            border: 1px solid #eee;
        }
        .booking-img {
            width: 200px;
            height: 150px;
            object-fit: cover;
        }
        .booking-details {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .event-name {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .booking-status {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-belum { background: #fff3e0; color: #e65100; }
        .status-verifikasi { background: #e3f2fd; color: #1565c0; }
        .status-berhasil { background: #e8f5e9; color: #2e7d32; }
        .status-batal { background: #ffebee; color: #c62828; }
        .event-info {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }
        .event-info i {
            width: 20px;
            color: #6b2737;
        }
        .booking-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px dashed #eee;
            padding-top: 15px;
        }
        .ticket-qty {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .total-price {
            font-weight: 700;
            color: #6b2737;
        }
        .booking-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn-action {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }
        .btn-upload { background: #6b2737; color: #fff; }
        .btn-cancel { background: #fff; border: 1px solid #ddd; color: #666; }
        .btn-upload:hover { background: #4a1a24; }
        .btn-cancel:hover { background: #f5f5f5; }

        /* Custom Modal */
        .custom-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }
        .custom-modal.show { display: flex; }
        .modal-box {
            background: #fff;
            width: 90%;
            max-width: 400px;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
        }
        .modal-box h3 { margin-bottom: 15px; font-family: 'Playfair Display', serif; }
        .modal-box p { font-size: 0.95rem; color: #666; margin-bottom: 25px; }
        .modal-footer { display: flex; gap: 10px; }
        .modal-footer button { flex: 1; padding: 12px; border-radius: 50px; font-weight: 700; cursor: pointer; border: none; }
        .btn-confirm { background: #6b2737; color: #fff; }
        .btn-close { background: #f0f0f0; color: #333; }
        
        #preview-img {
            max-width: 100%;
            max-height: 200px;
            margin-bottom: 15px;
            display: none;
            border-radius: 8px;
        }
        @media (max-width: 600px) {
            .booking-card { flex-direction: column; }
            .booking-img { width: 100%; height: 150px; }
        }
        .empty-state {
            text-align: center;
            padding: 80px 0;
            color: #999;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <?php include '../COMPONENTS/navbar.php'; ?>
    <?php include '../COMPONENTS/user_modals.php'; ?>

    <div class="bookings-container">
        <h1 class="page-title">Riwayat Tiket Saya</h1>

        <?php if (mysqli_num_rows($query_bookings) > 0): ?>
            <?php while ($book = mysqli_fetch_assoc($query_bookings)): ?>
                <div class="booking-card">
                    <?php
                    $imgPath = $book['gambar1'] ?: '';
                    if ($imgPath && !str_starts_with($imgPath, 'uploads/') && !str_starts_with($imgPath, 'images/')) {
                        $imgPath = 'images/storage/' . $imgPath;
                    }
                    ?>
                    <img src="../../<?php echo $imgPath ?: 'images/storage/default.png'; ?>" class="booking-img" alt="">
                    <div class="booking-details">
                        <div class="booking-header">
                            <div>
                                <div style="font-size: 0.8rem; font-weight: 600; color: #6b2737; margin-bottom: 4px; font-family: monospace;"><?php echo $book['no_pemesanan']; ?></div>
                                <div class="event-name"><?php echo htmlspecialchars($book['judul_event']); ?></div>
                                <div class="event-info">
                                    <span><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($book['tanggal_event'])); ?></span><br>
                                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($book['lokasi']); ?></span>
                                </div>
                            </div>
                            <?php 
                                $status_class = '';
                                if ($book['status'] === 'Belum Bayar') $status_class = 'status-belum';
                                elseif ($book['status'] === 'Menunggu Verifikasi') $status_class = 'status-verifikasi';
                                elseif ($book['status'] === 'Berhasil') $status_class = 'status-berhasil';
                                elseif ($book['status'] === 'Dibatalkan') $status_class = 'status-batal';
                            ?>
                            <span class="booking-status <?php echo $status_class; ?>"><?php echo $book['status']; ?></span>
                        </div>
                        <div class="booking-footer">
                            <div class="ticket-qty"><?php echo $book['jumlah_tiket']; ?> Tiket</div>
                            <div class="total-price">Rp <?php echo number_format($book['total_harga'], 0, ',', '.'); ?></div>
                        </div>
                        <?php if ($book['status'] !== 'Dibatalkan'): ?>
                        <div class="booking-actions">
                            <?php if ($book['status'] === 'Belum Bayar' || $book['status'] === 'Menunggu Verifikasi'): ?>
                                <button class="btn-action btn-upload" onclick="openUploadModal(<?php echo $book['id']; ?>)">
                                    <?php echo $book['bukti_pembayaran'] ? 'Ganti Bukti' : 'Unggah Bukti'; ?>
                                </button>
                            <?php endif; ?>
                            <button class="btn-action btn-cancel" onclick="openCancelModal(<?php echo $book['id']; ?>)">Batalkan</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-ticket-alt"></i>
                <p>Belum ada tiket yang dipesan.</p>
                <a href="events.php" class="btn-login" style="display:inline-block; margin-top:20px;">Cari Event Menarik</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Upload -->
    <div class="custom-modal" id="uploadModal">
        <div class="modal-box">
            <h3>Unggah Bukti</h3>
            <p>Silakan pilih file gambar bukti transfer Anda.</p>
            <form id="uploadForm">
                <input type="hidden" name="booking_id" id="upload_booking_id">
                <img id="preview-img" alt="Preview">
                <input type="file" name="bukti_pembayaran" id="fileInput" accept="image/*" style="margin-bottom:20px;" required onchange="previewFile()">
                <div class="modal-footer">
                    <button type="button" class="btn-close" onclick="closeModals()">Batal</button>
                    <button type="submit" class="btn-confirm">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Batal -->
    <div class="custom-modal" id="cancelModal">
        <div class="modal-box">
            <h3 style="color:#c62828;">Batalkan Pesanan?</h3>
            <p>Tindakan ini tidak dapat dibatalkan. Stok kursi akan dikembalikan.</p>
            <div class="modal-footer">
                <button class="btn-close" onclick="closeModals()">Tutup</button>
                <button class="btn-confirm" id="confirmCancelBtn" style="background:#c62828;">Ya, Batalkan</button>
            </div>
        </div>
    </div>

    <script>
        let currentBookingId = null;

        function openUploadModal(id) {
            currentBookingId = id;
            document.getElementById('upload_booking_id').value = id;
            document.getElementById('uploadModal').classList.add('show');
        }

        function openCancelModal(id) {
            currentBookingId = id;
            document.getElementById('cancelModal').classList.add('show');
        }

        function closeModals() {
            document.querySelectorAll('.custom-modal').forEach(m => m.classList.remove('show'));
            document.getElementById('preview-img').style.display = 'none';
        }

        function previewFile() {
            const preview = document.getElementById('preview-img');
            const file = document.getElementById('fileInput').files[0];
            const reader = new FileReader();
            reader.onloadend = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }
            if (file) reader.readAsDataURL(file);
        }

        document.getElementById('uploadForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('../../BACKEND/upload_payment.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert(data.message, 'Berhasil', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(data.message, 'Gagal', 'error');
                }
            });
        };

        document.getElementById('confirmCancelBtn').onclick = function() {
            const formData = new FormData();
            formData.append('booking_id', currentBookingId);
            formData.append('action', 'cancel');

            fetch('../../BACKEND/bookings_user_action.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert(data.message, 'Dibatalkan', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(data.message, 'Gagal', 'error');
                }
            });
        };
    </script>

    <?php include '../COMPONENTS/footer.php'; ?>
</body>
</html>