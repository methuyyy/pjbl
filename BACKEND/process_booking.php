<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $event_id = intval($_POST['event_id']);
    $jumlah_tiket = intval($_POST['jumlah_tiket']);

    if ($jumlah_tiket <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Jumlah tiket harus lebih dari 0.']);
        exit;
    }

    // Cek stok tiket dan harga
    $stmt = $conn->prepare("SELECT judul_event, sisa_kursi, harga FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        echo json_encode(['status' => 'error', 'message' => 'Event tidak ditemukan.']);
        exit;
    }

    if ($event['sisa_kursi'] < $jumlah_tiket) {
        echo json_encode(['status' => 'error', 'message' => 'Maaf, sisa tiket tidak mencukupi. Tersisa: ' . $event['sisa_kursi']]);
        exit;
    }

    $total_harga = $event['harga'] * $jumlah_tiket;

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Insert ke tabel bookings (Default: Belum Bayar)
        $stmt_book = $conn->prepare("INSERT INTO bookings (user_id, event_id, jumlah_tiket, total_harga, status) VALUES (?, ?, ?, ?, 'Belum Bayar')");
        $stmt_book->bind_param("iiid", $user_id, $event_id, $jumlah_tiket, $total_harga);
        $stmt_book->execute();
        
        // Ambil ID yang baru saja diinsert untuk generate no_pemesanan
        $booking_id = $conn->insert_id;
        $date_now = new DateTime();
        $no_pemesanan = "PW-BK-" . str_pad($booking_id, 4, '0', STR_PAD_LEFT) . "-" . $date_now->format('Ym');
        
        // Update no_pemesanan ke database
        $stmt_update_no = $conn->prepare("UPDATE bookings SET no_pemesanan = ? WHERE id = ?");
        $stmt_update_no->bind_param("si", $no_pemesanan, $booking_id);
        $stmt_update_no->execute();

        // Update sisa kursi di tabel events
        $stmt_update = $conn->prepare("UPDATE events SET sisa_kursi = sisa_kursi - ? WHERE id = ?");
        $stmt_update->bind_param("ii", $jumlah_tiket, $event_id);
        $stmt_update->execute();

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Pemesanan tiket ' . $event['judul_event'] . ' berhasil! No Pemesanan Anda: ' . $no_pemesanan . '. Silakan unggah bukti pembayaran di menu Tiket Saya agar Admin dapat memverifikasi pesanan Anda.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Gagal memproses pemesanan: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak valid.']);
}
?>