<?php
session_start();

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']);

include '../../BACKEND/config.php';

if (!isset($_GET['id'])) {
  die("Event tidak ditemukan");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT e.*, k.nama_kategori
    FROM events e
    LEFT JOIN kategori k ON e.kategori_id = k.id
    WHERE e.id = '$id'
");

$event = mysqli_fetch_assoc($query);

if (!$event) {
  die("Event tidak ditemukan");
}

$tanggal_event = date('d F Y', strtotime($event['tanggal_event']));

// Fetch event details
$benefits_query = mysqli_query($conn, "SELECT * FROM event_benefits WHERE event_id = $id");
$benefits = mysqli_fetch_all($benefits_query, MYSQLI_ASSOC);

$rundown_query = mysqli_query($conn, "SELECT * FROM event_rundown WHERE event_id = $id ORDER BY urutan ASC");
$rundown = mysqli_fetch_all($rundown_query, MYSQLI_ASSOC);

$speakers_query = mysqli_query($conn, "SELECT * FROM event_speakers WHERE event_id = $id");
$speakers = mysqli_fetch_all($speakers_query, MYSQLI_ASSOC);

$faq_query = mysqli_query($conn, "SELECT * FROM event_faqs WHERE event_id = $id");
$faqs = mysqli_fetch_all($faq_query, MYSQLI_ASSOC);

$terms_query = mysqli_query($conn, "SELECT * FROM event_terms WHERE event_id = $id ORDER BY urutan ASC");
$terms = mysqli_fetch_all($terms_query, MYSQLI_ASSOC);

$location_query = mysqli_query($conn, "SELECT * FROM event_locations WHERE event_id = $id LIMIT 1");
$location = mysqli_fetch_assoc($location_query);

// Calculate seat percentage
$seat_percent = 0;
if ($event['total_kursi'] > 0) {
  $sold = $event['total_kursi'] - $event['sisa_kursi'];
  $seat_percent = ($sold / $event['total_kursi']) * 100;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($event['judul_event']) ?> - Pawarti</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <!-- Google Material Icons Library -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Font Awesome for benefit icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --cream: #F7F3EE;
      --warm-white: #FDFAF6;
      --brown-deep: #3D1F0E;
      --brown-mid: #7A3B1E;
      --brown-light: #C0855A;
      --accent-gold: #C9A84C;
      --accent-terracotta: #B85C38;
      --text-body: #4A3728;
      --text-muted: #8A7060;
      --border: #E0D4C8;
      --dark-maroon: #5C1A1A;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--warm-white);
      color: var(--text-body);
      line-height: 1.7;
    }


    /* BREADCRUMB */
    .breadcrumb {
      padding: 14px 5%;
      background: var(--cream);
      border-bottom: 1px solid var(--border);
      font-size: 0.8rem;
      color: var(--text-muted);
    }

    .breadcrumb a {
      color: var(--text-muted);
      text-decoration: none;
    }

    .breadcrumb a:hover {
      color: var(--brown-mid);
      text-decoration: underline;
    }

    .breadcrumb span {
      margin: 0 6px;
    }

    /* HERO */
    .hero {
      background: var(--cream);
      padding: 40px 5% 0;
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 3rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .hero-meta {
      padding-bottom: 40px;
    }

    .badge-unggulan {
      display: inline-block;
      background: var(--accent-gold);
      color: #5C3D00;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 4px;
      margin-bottom: 14px;
    }

    .hero-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.4rem;
      font-weight: 700;
      color: var(--brown-deep);
      line-height: 1.25;
      margin-bottom: 10px;
    }

    .hero-tagline {
      font-size: 1rem;
      color: var(--text-muted);
      margin-bottom: 18px;
    }

    .organizer-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 22px;
    }

    .organizer-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--brown-mid);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-weight: 700;
      font-size: 0.8rem;
    }

    .organizer-name {
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--brown-deep);
    }

    .organizer-label {
      font-size: 0.78rem;
      color: var(--text-muted);
    }

    .event-stats {
      display: flex;
      flex-wrap: wrap;
      gap: 18px;
      margin-bottom: 28px;
    }

    .stat-item {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 0.875rem;
      color: var(--text-body);
    }

    .stat-icon {
      width: 32px;
      height: 32px;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--brown-mid);
    }

    .stat-icon .material-icons {
      font-size: 1.2rem;
    }

    .stat-label {
      font-size: 0.72rem;
      color: var(--text-muted);
      display: block;
    }

    .stat-value {
      font-weight: 600;
      display: block;
      color: var(--brown-deep);
    }

    .hero-image-wrap {
      border-radius: 16px;
      overflow: hidden;
      height: 280px;
      background: #ddd;
      position: relative;
      align-self: start;
    }

    .hero-image-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hero-img-placeholder {
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #c9845a 0%, #7a3b1e 60%, #3d1f0e 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255, 255, 255, 0.3);
      font-size: 3rem;
      font-family: 'Playfair Display', serif;
    }

    /* STICKY BOOK CARD */
    .layout-wrapper {
      max-width: 1200px;
      margin: 0 auto;
      padding: 36px 5%;
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 3rem;
      align-items: start;
    }

    .book-card {
      background: #fff;
      border: 1.5px solid var(--border);
      border-radius: 16px;
      padding: 28px;
      position: sticky;
      top: 80px;
      box-shadow: 0 4px 24px rgba(61, 31, 14, 0.07);
    }

    .price-main {
      font-family: 'Playfair Display', serif;
      font-size: 1.9rem;
      font-weight: 700;
      color: var(--dark-maroon);
      margin-bottom: 4px;
    }

    .price-sub {
      font-size: 0.8rem;
      color: var(--text-muted);
      margin-bottom: 20px;
    }

    .seat-bar-wrap {
      margin-bottom: 20px;
    }

    .seat-bar-label {
      display: flex;
      justify-content: space-between;
      font-size: 0.8rem;
      color: var(--text-muted);
      margin-bottom: 6px;
    }

    .seat-bar-track {
      height: 6px;
      background: var(--border);
      border-radius: 99px;
      overflow: hidden;
    }

    .seat-bar-fill {
      height: 100%;
      background: var(--accent-terracotta);
      border-radius: 99px;
      width: 98%;
      /* 98 dari 100 terisi */
    }

    .seat-count {
      text-align: center;
      font-size: 0.82rem;
      color: var(--accent-terracotta);
      font-weight: 600;
      margin-top: 6px;
    }

    .qty-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 10px 16px;
      margin-bottom: 14px;
    }

    .qty-btn {
      width: 32px;
      height: 32px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: var(--cream);
      cursor: pointer;
      font-size: 1rem;
      font-weight: 600;
      color: var(--brown-deep);
    }

    .qty-num {
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--brown-deep);
    }

    .qty-label {
      font-size: 0.8rem;
      color: var(--text-muted);
    }

    .btn-pesan {
      width: 100%;
      padding: 14px;
      background: var(--dark-maroon);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      margin-bottom: 12px;
      letter-spacing: 0.3px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-pesan:hover {
      background: var(--brown-mid);
    }

    .btn-wishlist {
      width: 100%;
      padding: 12px;
      background: transparent;
      color: var(--dark-maroon);
      border: 1.5px solid var(--dark-maroon);
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .card-meta-list {
      margin-top: 22px;
      border-top: 1px solid var(--border);
      padding-top: 18px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .card-meta-item {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      font-size: 0.82rem;
      color: var(--text-muted);
    }

    .card-meta-item .material-icons {
      font-size: 1.1rem;
      color: var(--brown-light);
    }

    .card-meta-item strong {
      color: var(--text-body);
      display: block;
    }

    /* CONTENT */
    .content-section {
      margin-bottom: 36px;
    }

    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--brown-deep);
      margin-bottom: 14px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--cream);
    }

    .section-title::after {
      content: '';
      display: block;
      width: 40px;
      height: 3px;
      background: var(--accent-gold);
      margin-top: 8px;
    }

    .prose p {
      margin-bottom: 1rem;
      font-size: 0.95rem;
      color: var(--text-body);
    }

    .prose ul {
      padding-left: 1.2rem;
      margin-bottom: 1rem;
    }

    .prose ul li {
      margin-bottom: 6px;
      font-size: 0.95rem;
      color: var(--text-body);
    }

    /* TIMELINE ACARA */
    .timeline {
      display: flex;
      flex-direction: column;
      gap: 0;
    }

    .timeline-item {
      display: flex;
      gap: 16px;
      position: relative;
    }

    .timeline-item:not(:last-child)::before {
      content: '';
      position: absolute;
      left: 15px;
      top: 32px;
      bottom: 0;
      width: 2px;
      background: var(--border);
    }

    .timeline-dot {
      width: 32px;
      height: 32px;
      min-width: 32px;
      background: var(--cream);
      border: 2px solid var(--accent-gold);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
      color: var(--brown-mid);
      position: relative;
      z-index: 1;
    }

    .timeline-body {
      padding: 4px 0 24px;
    }

    .timeline-time {
      font-size: 0.75rem;
      font-weight: 700;
      color: var(--accent-gold);
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .timeline-label {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--brown-deep);
    }

    .timeline-desc {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-top: 3px;
    }

    /* WHAT YOU'LL GET */
    .benefits-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .benefit-item {
      background: var(--cream);
      border-radius: 12px;
      padding: 16px;
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }

    .benefit-icon {
      width: 36px;
      height: 36px;
      min-width: 36px;
      background: var(--accent-gold);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
    }

    .benefit-icon .material-icons {
      font-size: 1.2rem;
    }

    .benefit-title {
      font-weight: 600;
      font-size: 0.875rem;
      color: var(--brown-deep);
      margin-bottom: 3px;
    }

    .benefit-desc {
      font-size: 0.8rem;
      color: var(--text-muted);
    }

    /* SPEAKER */
    .speaker-card {
      background: var(--cream);
      border-radius: 14px;
      padding: 22px;
      display: flex;
      gap: 20px;
      align-items: flex-start;
    }

    .speaker-avatar {
      width: 72px;
      height: 72px;
      min-width: 72px;
      border-radius: 12px;
      background: var(--brown-mid);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      font-weight: 700;
    }

    .speaker-name {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--brown-deep);
      margin-bottom: 3px;
    }

    .speaker-role {
      font-size: 0.82rem;
      color: var(--accent-terracotta);
      font-weight: 600;
      margin-bottom: 8px;
    }

    .speaker-bio {
      font-size: 0.85rem;
      color: var(--text-muted);
    }

    /* LOKASI */
    .lokasi-box {
      background: var(--cream);
      border-radius: 14px;
      padding: 20px;
      display: flex;
      gap: 16px;
    }

    .map-placeholder {
      width: 130px;
      min-width: 130px;
      height: 100px;
      background: #d9cfc6;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
    }

    .map-placeholder .material-icons {
      font-size: 2rem;
    }

    .lokasi-info {
      flex: 1;
    }

    .lokasi-name {
      font-weight: 700;
      font-size: 0.95rem;
      color: var(--brown-deep);
      margin-bottom: 4px;
    }

    .lokasi-address {
      font-size: 0.82rem;
      color: var(--text-muted);
      margin-bottom: 10px;
    }

    .btn-maps {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 16px;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 7px;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--dark-maroon);
      text-decoration: none;
      cursor: pointer;
    }

    .btn-maps .material-icons {
      font-size: 1rem;
    }

    /* RELATED EVENTS */
    .related-section {
      background: var(--cream);
      padding: 40px 5%;
      margin-top: 20px;
    }

    .related-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--brown-deep);
      margin-bottom: 24px;
      text-align: center;
    }

    .related-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .related-card {
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      border: 1px solid var(--border);
    }

    .related-img {
      height: 140px;
      background: linear-gradient(135deg, #c9845a, #3d1f0e);
      position: relative;
    }

    .related-img-2 {
      background: linear-gradient(135deg, #4a7c59, #1a3d2a);
    }

    .related-img-3 {
      background: linear-gradient(135deg, #5a6cc9, #1a2a6c);
    }

    .related-category {
      position: absolute;
      bottom: 10px;
      left: 12px;
      background: rgba(0, 0, 0, 0.45);
      color: #fff;
      font-size: 0.7rem;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 4px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .related-body {
      padding: 16px;
    }

    .related-event-title {
      font-weight: 700;
      font-size: 0.95rem;
      color: var(--brown-deep);
      margin-bottom: 6px;
    }

    .related-meta {
      font-size: 0.78rem;
      color: var(--text-muted);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .related-meta .material-icons {
      font-size: 0.9rem;
      color: var(--text-muted);
    }

    .related-price {
      font-weight: 700;
      color: var(--dark-maroon);
      font-size: 0.95rem;
    }

    /* FAQ */
    .faq-item {
      border-bottom: 1px solid var(--border);
      padding: 16px 0;
    }

    .faq-question {
      font-weight: 600;
      font-size: 0.95rem;
      color: var(--brown-deep);
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
    }

    .faq-question:hover {
      color: var(--dark-maroon);
    }

    .faq-answer {
      font-size: 0.875rem;
      color: var(--text-muted);
      margin-top: 8px;
      display: none;
    }

    .faq-item.open .faq-answer {
      display: block;
    }

    .faq-arrow {
      transition: transform 0.2s;
    }

    .faq-item.open .faq-arrow {
      transform: rotate(180deg);
    }

    /* Booking Modal Styles */
    .booking-modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.8);
      z-index: 2000;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(5px);
    }

    .booking-modal.show {
      display: flex;
    }

    .modal-content {
      background: #fff;
      width: 100%;
      max-width: 450px;
      border-radius: 24px;
      padding: 32px;
      color: #333;
      position: relative;
    }

    .modal-close {
      position: absolute;
      top: 20px;
      right: 20px;
      cursor: pointer;
      font-size: 1.2rem;
      color: #999;
    }

    .modal-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      color: #000;
    }

    .booking-info {
      background: #f9f9f9;
      padding: 15px;
      border-radius: 12px;
      margin-bottom: 20px;
    }

    .booking-info-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
      font-size: 0.9rem;
    }

    .booking-info-item:last-child {
      margin-bottom: 0;
    }

    .booking-form .form-group {
      margin-bottom: 20px;
    }

    .booking-form label {
      display: block;
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .booking-form input {
      width: 100%;
      padding: 12px 16px;
      border: 1.5px solid #eee;
      border-radius: 12px;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.3s;
    }

    .booking-form input:focus {
      border-color: var(--dark-maroon);
    }

    .total-price-display {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--dark-maroon);
      margin: 20px 0;
      text-align: right;
    }

    .btn-confirm-booking {
      width: 100%;
      padding: 16px;
      background: var(--dark-maroon);
      color: #fff;
      border: none;
      border-radius: 50px;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-confirm-booking:hover {
      background: var(--brown-mid);
    }

    /* Success Modal */
    .success-modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.8);
      z-index: 2100;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(5px);
    }

    .success-modal.show {
      display: flex;
    }

    .success-content {
      background: #fff;
      width: 100%;
      max-width: 400px;
      border-radius: 24px;
      padding: 40px;
      text-align: center;
    }

    .success-icon {
      width: 80px;
      height: 80px;
      background: #e8f5e9;
      color: #2e7d32;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      margin: 0 auto 20px;
    }

    .btn-done {
      margin-top: 25px;
      width: 100%;
      padding: 14px;
      background: var(--dark-maroon);
      color: #fff;
      border: none;
      border-radius: 50px;
      font-weight: 700;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <?php include '../COMPONENTS/navbar.php'; ?>
  <?php include '../COMPONENTS/user_modals.php'; ?>


  <!-- HERO -->
  <div style="background: var(--cream);">
    <div class="hero">
      <div class="hero-meta">
        <div class="badge-unggulan" style="display: inline-flex; align-items: center; gap: 4px;"><i class="material-icons" style="font-size: 0.9rem;">star</i> Unggulan</div>
        <h1 class="hero-title">
          <?= nl2br(htmlspecialchars($event['judul_event'])) ?>
        </h1>
        <p class="hero-tagline">
          <?= htmlspecialchars($event['featured_sub'] ?: 'Event budaya terbaik pilihan Pawarti') ?>
        </p>

        <div class="organizer-row">
          <div class="organizer-avatar">PW</div>
          <div>
            <div class="organizer-name">Pawarti Cultural Arts</div>
            <div class="organizer-label">Penyelenggara Terverifikasi</div>
          </div>
        </div>

        <div class="event-stats">
          <div class="stat-item">
            <div class="stat-icon"><i class="material-icons">calendar_today</i></div>
            <div>
              <span class="stat-label">Tanggal</span>
              <span class="stat-value"><?= $tanggal_event ?></span>
            </div>
          </div>
          <div class="stat-item">
            <div class="stat-icon"><i class="material-icons">access_time</i></div>
            <div>
              <span class="stat-label">Waktu</span>
              <span class="stat-value">09.00 – 16.00 WIB</span>
            </div>
          </div>
          <div class="stat-item">
            <div class="stat-icon"><i class="material-icons">place</i></div>
            <div>
              <span class="stat-label">Lokasi</span>
              <span class="stat-value">
                <?= htmlspecialchars($event['lokasi']) ?>
              </span>
            </div>
          </div>
          <div class="stat-item">
            <div class="stat-icon"><i class="material-icons">group</i></div>
            <div>
              <span class="stat-label">Kapasitas</span>
              <span class="stat-value">
                <?= $event['total_kursi'] ?> Peserta
              </span>
            </div>
          </div>
        </div>

        <!-- Share row -->
        <div style="display: flex; gap: 10px; align-items: center;">
          <span style="font-size:0.8rem; color: var(--text-muted);">Bagikan:</span>
          <button style="display: flex; align-items: center; gap: 4px; padding:6px 14px; border:1px solid var(--border); border-radius:6px; background:#fff; font-size:0.8rem; cursor:pointer; color: var(--text-body);"><i class="material-icons" style="font-size: 0.9rem;">content_copy</i> Salin Tautan</button>
          <button style="display: flex; align-items: center; gap: 4px; padding:6px 14px; border:1px solid var(--border); border-radius:6px; background:#fff; font-size:0.8rem; cursor:pointer; color: var(--text-body);"><i class="material-icons" style="font-size: 0.9rem;">phone_android</i> WhatsApp</button>
        </div>
      </div>

      <!-- Hero image -->
      <div class="hero-image-wrap">

        <?php if (!empty($event['gambar1'])): ?>

          <?php
          $imgPath = $event['gambar1'] ?: '';
          if ($imgPath && !str_starts_with($imgPath, 'uploads/') && !str_starts_with($imgPath, 'images/')) {
            $imgPath = 'images/storage/' . $imgPath;
          }
          ?>
          <img
            src="../../<?= htmlspecialchars($imgPath ?: 'images/storage/default.png'); ?>"
            alt="<?= htmlspecialchars($event['judul_event']); ?>"
            style="
                  width:100%;
                  height:100%;
                  object-fit:cover;
                  border-radius:24px;
              ">

        <?php else: ?>

          <div class="hero-img-placeholder">
            <span style="opacity:0.5; display:flex; align-items:center;">
              <i class="material-icons" style="font-size:4rem;">image</i>
            </span>
          </div>

        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- MAIN LAYOUT -->
  <div class="layout-wrapper">

    <!-- LEFT CONTENT -->
    <main>

      <!-- DESKRIPSI -->
      <section class="content-section">
        <h2 class="section-title">Tentang Event Ini</h2>
        <div class="prose">
          <?= nl2br(htmlspecialchars($event['deskripsi'])) ?>
        </div>
      </section>

      <!-- YANG AKAN KAMU DAPATKAN -->
      <?php if (!empty($benefits)): ?>
        <section class="content-section">
          <h2 class="section-title">Yang Akan Kamu Dapatkan</h2>
          <div class="benefits-grid">
            <?php foreach ($benefits as $benefit): ?>
              <div class="benefit-item">
                <div class="benefit-icon">
                  <?php if (!empty($benefit['icon'])): ?>
                    <i class="fas <?= htmlspecialchars($benefit['icon']) ?>" style="font-size:1.2rem;"></i>
                  <?php else: ?>
                    <i class="material-icons">check_circle</i>
                  <?php endif; ?>
                </div>
                <div>
                  <div class="benefit-title"><?= htmlspecialchars($benefit['title']) ?></div>
                  <?php if (!empty($benefit['description'])): ?>
                    <div class="benefit-desc"><?= htmlspecialchars($benefit['description']) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      <?php else: ?>
        <section class="content-section">
          <h2 class="section-title">Yang Akan Kamu Dapatkan</h2>
          <p style="color:var(--text-muted);">Manfaat event akan segera ditambahkan.</p>
        </section>
      <?php endif; ?>

      <!-- RUNDOWN ACARA -->
      <?php if (!empty($rundown)): ?>
        <section class="content-section">
          <h2 class="section-title">Rundown Acara</h2>
          <div class="timeline">
            <?php foreach ($rundown as $index => $item): ?>
              <div class="timeline-item">
                <div class="timeline-dot"><?= $index + 1 ?></div>
                <div class="timeline-body">
                  <div class="timeline-time"><?= date('H.i', strtotime($item['jam_mulai'])) ?> – <?= date('H.i', strtotime($item['jam_selesai'])) ?></div>
                  <div class="timeline-label"><?= htmlspecialchars($item['title']) ?></div>
                  <?php if (!empty($item['description'])): ?>
                    <div class="timeline-desc"><?= htmlspecialchars($item['description']) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      <?php else: ?>
        <section class="content-section">
          <h2 class="section-title">Rundown Acara</h2>
          <p style="color:var(--text-muted);">Jadwal acara akan segera ditambahkan.</p>
        </section>
      <?php endif; ?>

      <!-- NARASUMBER -->
      <?php if (!empty($speakers)): ?>
        <section class="content-section">
          <h2 class="section-title">Narasumber</h2>
          <?php foreach ($speakers as $speaker): ?>
            <div class="speaker-card" style="margin-bottom: 16px;">
              <div class="speaker-avatar">
                <?php if (!empty($speaker['foto'])): ?>
                  <?php
                  $imgPath = $speaker['foto'];
                  if ($imgPath && !str_starts_with($imgPath, 'uploads/') && !str_starts_with($imgPath, 'images/')) {
                    $imgPath = 'images/storage/' . $imgPath;
                  }
                  ?>
                  <img src="../../<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($speaker['nama']) ?>" style="width:100%; height:100%; object-fit:cover; border-radius:12px;">
                <?php else: ?>
                  <?= strtoupper(substr($speaker['nama'], 0, 2)) ?>
                <?php endif; ?>
              </div>
              <div>
                <div class="speaker-name"><?= htmlspecialchars($speaker['nama']) ?></div>
                <?php if (!empty($speaker['jabatan'])): ?>
                  <div class="speaker-role"><?= htmlspecialchars($speaker['jabatan']) ?></div>
                <?php endif; ?>
                <?php if (!empty($speaker['bio'])): ?>
                  <div class="speaker-bio"><?= nl2br(htmlspecialchars($speaker['bio'])) ?></div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </section>
      <?php else: ?>
        <section class="content-section">
          <h2 class="section-title">Narasumber</h2>
          <p style="color:var(--text-muted);">Narasumber akan segera diumumkan.</p>
        </section>
      <?php endif; ?>

      <!-- LOKASI -->
      <section class="content-section">
        <h2 class="section-title">Lokasi Event</h2>
        <div class="lokasi-box">
          <div class="map-placeholder"><i class="material-icons">map</i></div>
          <div class="lokasi-info">
            <?php if ($location): ?>
              <?php if (!empty($location['nama_tempat'])): ?>
                <div class="lokasi-name"><?= htmlspecialchars($location['nama_tempat']) ?></div>
              <?php endif; ?>
              <?php if (!empty($location['alamat'])): ?>
                <div class="lokasi-address"><?= nl2br(htmlspecialchars($location['alamat'])) ?></div>
              <?php endif; ?>
              <?php if (!empty($location['maps_link'])): ?>
                <a class="btn-maps" href="<?= htmlspecialchars($location['maps_link']) ?>" target="_blank"><i class="material-icons">explore</i> Lihat di Google Maps</a>
              <?php endif; ?>
              <?php if (!empty($location['catatan'])): ?>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 12px;"><?= nl2br(htmlspecialchars($location['catatan'])) ?></p>
              <?php endif; ?>
            <?php else: ?>
              <div class="lokasi-name"><?= htmlspecialchars($event['lokasi']) ?></div>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- SYARAT & KETENTUAN -->
      <?php if (!empty($terms)): ?>
        <section class="content-section">
          <h2 class="section-title">Syarat & Ketentuan</h2>
          <div class="prose">
            <ul>
              <?php foreach ($terms as $term): ?>
                <li><?= htmlspecialchars($term['isi']) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </section>
      <?php else: ?>
        <section class="content-section">
          <h2 class="section-title">Syarat & Ketentuan</h2>
          <p style="color:var(--text-muted);">Syarat dan ketentuan akan segera ditambahkan.</p>
        </section>
      <?php endif; ?>

      <!-- FAQ -->
      <?php if (!empty($faqs)): ?>
        <section class="content-section">
          <h2 class="section-title">Pertanyaan Umum</h2>
          <div>
            <?php foreach ($faqs as $index => $faq): ?>
              <div class="faq-item <?= $index === 0 ? 'open' : '' ?>">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <?= htmlspecialchars($faq['question']) ?>
                  <span class="faq-arrow"><i class="material-icons">arrow_drop_down</i></span>
                </div>
                <div class="faq-answer"><?= nl2br(htmlspecialchars($faq['answer'])) ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      <?php else: ?>
        <section class="content-section">
          <h2 class="section-title">Pertanyaan Umum</h2>
          <p style="color:var(--text-muted);">Pertanyaan yang sering ditanyakan akan segera ditambahkan.</p>
        </section>
      <?php endif; ?>

    </main>

    <!-- RIGHT STICKY CARD -->
    <aside>
      <div class="book-card">
        <div class="price-main">
          Rp <?= number_format($event['harga'], 0, ',', '.') ?>
        </div>
        <div class="price-sub">per orang · termasuk makan siang & perlengkapan</div>

        <div class="seat-bar-wrap">
          <div class="seat-bar-label">
            <span>Ketersediaan Kursi</span>
            <span style="color: var(--accent-terracotta); font-weight: 600;">
              <?php if ($seat_percent > 80): ?>
                Hampir Habis!
              <?php elseif ($seat_percent > 50): ?>
                Cepat Ambil!
              <?php else: ?>
                Tersedia
              <?php endif; ?>
            </span>
          </div>
          <div class="seat-bar-track">
            <div class="seat-bar-fill" style="width: <?= min(100, $seat_percent) ?>%;"></div>
          </div>
          <div class="seat-count">
            Tersisa <?= $event['sisa_kursi'] ?> kursi dari <?= $event['total_kursi'] ?>
          </div>
        </div>

        <div class="qty-row">
          <div>
            <div class="qty-label">Jumlah Tiket</div>
          </div>
          <div style="display:flex; align-items:center; gap: 12px;">
            <button class="qty-btn" onclick="changeQty(-1)">−</button>
            <span class="qty-num" id="qty">1</span>
            <button class="qty-btn" onclick="changeQty(1)">+</button>
          </div>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 16px; padding: 10px 0; border-top: 1px solid var(--border);">
          <span style="color: var(--text-muted);">Total</span>
          <span id="total-price" style="font-weight: 700; color: var(--brown-deep);">Rp <?= number_format($event['harga'], 0, ',', '.') ?></span>
        </div>

        <button class="btn-pesan" onclick="openBookingModal(<?= htmlspecialchars(json_encode($event)) ?>)"><i class="material-icons" style="font-size: 1.1rem;">confirmation_number</i> Pesan Sekarang</button>
        <button class="btn-wishlist"><i class="material-icons" style="font-size: 1.1rem;">favorite_border</i> Simpan ke Wishlist</button>

        <div class="card-meta-list">
          <div class="card-meta-item">
            <span><i class="material-icons">calendar_today</i></span>
            <div><strong>Jumat, 10 Juli 2026</strong> 09.00 – 16.00 WIB</div>
          </div>
          <div class="card-meta-item">
            <span><i class="material-icons">place</i></span>
            <div><strong>Sanggar Batik Laweyan</strong> Solo, Jawa Tengah</div>
          </div>
          <div class="card-meta-item">
            <span><i class="material-icons">sync</i></span>
            <div><strong>Penukaran tanggal dapat dilakukan H-3 sebelum acara</strong></div>
          </div>
          <div class="card-meta-item">
            <span><i class="material-icons">chat_bubble_outline</i></span>
            <div>Ada pertanyaan? <a href="#" style="color: var(--dark-maroon); font-weight: 600;">Chat Penyelenggara</a></div>
          </div>
        </div>
      </div>
    </aside>

  </div>

  <!-- RELATED EVENTS -->
  <div class="related-section">
    <h2 class="related-title">Event Budaya Lainnya</h2>
    <div class="related-grid">
      <div class="related-card">
        <div class="related-img" style="background: linear-gradient(135deg, #c9845a, #3d1f0e);">
          <span class="related-category">Seni Pertunjukan</span>
        </div>
        <div class="related-body">
          <div class="related-event-title">Pasar Rakyat Surabaya</div>
          <div class="related-meta"><i class="material-icons">place</i> Surabaya &nbsp;·&nbsp; <i class="material-icons">calendar_today</i> 15 Juni 2026</div>
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <div class="related-price">Rp 10.000</div>
            <span style="font-size:0.75rem; background:#ffeaea; color:#a30000; padding:3px 10px; border-radius:4px; font-weight:600;">Tiket Habis</span>
          </div>
        </div>
      </div>
      <div class="related-card">
        <div class="related-img" style="background: linear-gradient(135deg, #4a7c59, #1a3d2a);">
          <span class="related-category">Workshop Budaya</span>
        </div>
        <div class="related-body">
          <div class="related-event-title">Festival Reog Ponorogo</div>
          <div class="related-meta"><i class="material-icons">place</i> Ponorogo &nbsp;·&nbsp; <i class="material-icons">calendar_today</i> 20 Juni 2026</div>
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <div class="related-price">Rp 50.000</div>
            <span style="font-size:0.75rem; color: var(--text-muted);">Sisa 98 kursi</span>
          </div>
        </div>
      </div>
      <div class="related-card">
        <div class="related-img" style="background: linear-gradient(135deg, #8b6914, #4a3800);">
          <span class="related-category">Kuliner Tradisional</span>
        </div>
        <div class="related-body">
          <div class="related-event-title">Kelas Memasak Gudeg Jogja</div>
          <div class="related-meta"><i class="material-icons">place</i> Yogyakarta &nbsp;·&nbsp; <i class="material-icons">calendar_today</i> 25 Juli 2026</div>
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <div class="related-price">Rp 85.000</div>
            <span style="font-size:0.75rem; color: var(--text-muted);">Sisa 30 kursi</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Modal -->
  <div class="booking-modal" id="bookingModal">
    <div class="modal-content">
      <div class="modal-close" onclick="closeBookingModal()">&times;</div>
      <div class="modal-title">Pesan Tiket</div>

      <div class="booking-info">
        <div class="booking-info-item">
          <span>Event:</span>
          <strong id="modalEventTitle">-</strong>
        </div>
        <div class="booking-info-item">
          <span>Harga:</span>
          <strong id="modalEventPrice">-</strong>
        </div>
        <div class="booking-info-item">
          <span>Sisa Kursi:</span>
          <strong id="modalEventSeats">-</strong>
        </div>
      </div>

      <form class="booking-form" id="bookingForm">
        <input type="hidden" name="event_id" id="modalEventId">
        <div class="form-group">
          <label>Jumlah Tiket</label>
          <input type="number" name="jumlah_tiket" id="bookingQty" min="1" value="1" oninput="calculateBookingTotal()">
        </div>

        <div class="total-price-display">
          Total: <span id="modalTotalPrice">Rp 0</span>
        </div>

        <button type="submit" class="btn-confirm-booking">Konfirmasi Pesanan</button>
      </form>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="success-modal" id="successModal">
    <div class="success-content">
      <div class="success-icon"><i class="fas fa-check"></i></div>
      <h2 style="margin-bottom:10px;">Terima Kasih!</h2>
      <p id="successMessage">Pemesanan Anda berhasil. Silakan cek menu Tiket Saya untuk detail pembayaran.</p>
      <button class="btn-done" onclick="window.location.href='bookings_user.php'">Lihat Tiket Saya</button>
    </div>
  </div>

  <div class="footer-bottom">© 2024 Pawarti. Semua hak cipta dilindungi.</div>

  <script>
    let qty = 1;
    const basePrice = <?= $event['harga'] ?>;
    const is_logged_in = <?= json_encode($is_logged_in) ?>;
    let selectedEventPrice = 0;

    function changeQty(delta) {
      qty = Math.max(1, Math.min(<?= $event['sisa_kursi'] ?>, qty + delta));
      document.getElementById('qty').textContent = qty;
      document.getElementById('total-price').textContent = 'Rp ' + (qty * basePrice).toLocaleString('id-ID');
    }

    function toggleFaq(el) {
      const item = el.parentElement;
      item.classList.toggle('open');
    }

    function openBookingModal(eventData) {
      if (!eventData) return;

      if (!is_logged_in) {
        showAlert('Anda harus login terlebih dahulu untuk memesan tiket.', 'Akses Ditolak', 'error');
        setTimeout(() => window.location.href = 'loginbaru.php', 2000);
        return;
      }

      document.getElementById('modalEventId').value = eventData.id;
      document.getElementById('modalEventTitle').textContent = eventData.judul_event;
      document.getElementById('modalEventPrice').textContent = eventData.harga > 0 ? 'Rp ' + parseInt(eventData.harga).toLocaleString('id-ID') : 'Gratis';
      document.getElementById('modalEventSeats').textContent = eventData.sisa_kursi;

      selectedEventPrice = parseInt(eventData.harga);
      document.getElementById('bookingQty').value = 1;
      document.getElementById('bookingQty').max = eventData.sisa_kursi;

      calculateBookingTotal();
      document.getElementById('bookingModal').classList.add('show');
    }

    function closeBookingModal() {
      document.getElementById('bookingModal').classList.remove('show');
    }

    function calculateBookingTotal() {
      const qtyVal = document.getElementById('bookingQty').value || 0;
      const total = qtyVal * selectedEventPrice;
      document.getElementById('modalTotalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('bookingForm').onsubmit = function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch('../../BACKEND/process_booking.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            closeBookingModal();
            document.getElementById('successMessage').textContent = data.message;
            document.getElementById('successModal').classList.add('show');
          } else {
            showAlert(data.message, 'Gagal Memesan', 'error');
          }
        })
        .catch(err => {
          console.error('Error:', err);
          showAlert('Terjadi kesalahan saat memproses pesanan.', 'Error', 'error');
        });
    };
  </script>

  <!-- Debug Info -->
  <div style="max-width:1000px; margin:40px auto; padding:20px; background:#fff; border:1px solid #eee; border-radius:8px; font-family:monospace; font-size:12px;">
    <h3>Debug Data (Remove Later):</h3>
    <details>
      <summary>Benefits</summary>
      <pre><?php print_r($benefits); ?></pre>
    </details>
    <details>
      <summary>Rundown</summary>
      <pre><?php print_r($rundown); ?></pre>
    </details>
    <details>
      <summary>Speakers</summary>
      <pre><?php print_r($speakers); ?></pre>
    </details>
    <details>
      <summary>Terms</summary>
      <pre><?php print_r($terms); ?></pre>
    </details>
    <details>
      <summary>FAQs</summary>
      <pre><?php print_r($faqs); ?></pre>
    </details>
    <details>
      <summary>Location</summary>
      <pre><?php print_r($location); ?></pre>
    </details>
  </div>

  <?php include '../COMPONENTS/footer.php'; ?>
</body>

</html>