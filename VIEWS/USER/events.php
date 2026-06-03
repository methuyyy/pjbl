<?php
session_start();
include '../../BACKEND/config.php';

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']);

// Fetch categories for filtering
$query_kategori = mysqli_query($conn, "SELECT * FROM kategori");
$categories = [];
while ($row = mysqli_fetch_assoc($query_kategori)) {
  $categories[] = $row;
}

// Fetch events
$query_events = mysqli_query($conn, "SELECT e.*, k.nama_kategori FROM events e LEFT JOIN kategori k ON e.kategori_id = k.id ORDER BY e.tanggal_event ASC");

// Fetch Featured Event
$query_featured = mysqli_query($conn, "SELECT * FROM events WHERE is_featured = 1 ORDER BY id DESC LIMIT 1");
$featured = mysqli_fetch_assoc($query_featured);

// If no featured event, pick the latest one
if (!$featured) {
  $query_latest = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC LIMIT 1");
  $featured = mysqli_fetch_assoc($query_latest);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Budaya — Pawerti</title>
  <link rel="stylesheet" href="../../CSS/website.css">
  <link rel="stylesheet" href="../../CSS/events.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* New Card Design based on user image */
    .event-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 25px;
      padding: 40px 60px;
      max-width: 1400px;
      margin: 0 auto;
    }

    .modern-card {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      aspect-ratio: 3/4;
      background: #000;
      color: #fff;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease;
    }

    .modern-card:hover {
      transform: translateY(-10px);
    }

    .card-slider {
      position: absolute;
      inset: 0;
      display: flex;
      transition: transform 0.5s ease;
    }

    .slider-img {
      min-width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.7;
    }

    .card-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.1) 100%);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 24px;
    }

    .slider-dots {
      position: absolute;
      bottom: 180px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 6px;
    }

    .dot {
      width: 6px;
      height: 6px;
      background: rgba(255, 255, 255, 0.4);
      border-radius: 50%;
      transition: all 0.3s;
    }

    .dot.active {
      background: #fff;
      width: 18px;
      border-radius: 10px;
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .card-location {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 20px;
    }

    .card-info-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
      padding: 12px 0;
      margin-bottom: 24px;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.85rem;
    }

    .info-item i {
      opacity: 0.7;
      font-size: 0.9rem;
    }

    .card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-price {
      background: rgba(255, 255, 255, 0.15);
      padding: 10px 20px;
      border-radius: 50px;
      font-weight: 600;
      backdrop-filter: blur(10px);
    }

    .btn-reserve {
      background: #fff;
      color: #000;
      padding: 12px 24px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.95rem;
      flex: 1;
      text-align: center;
      margin-left: 15px;
      transition: background 0.3s;
    }

    .btn-reserve:hover {
      background: #f0f0f0;
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
      border-color: #6b2737;
    }

    .total-price-display {
      font-size: 1.2rem;
      font-weight: 700;
      color: #6b2737;
      margin: 20px 0;
      text-align: right;
    }

    .btn-confirm-booking {
      width: 100%;
      padding: 16px;
      background: #6b2737;
      color: #fff;
      border: none;
      border-radius: 50px;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-confirm-booking:hover {
      background: #4a1a24;
    }

    /* Thank You Modal */
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
      background: #6b2737;
      color: #fff;
      border: none;
      border-radius: 50px;
      font-weight: 700;
      cursor: pointer;
    }

    /* Header & Filter styles */
    .page-header {
      padding: 100px 60px 20px;
      text-align: center;
      background: #fff;
    }

    .page-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 15px;
    }

    .filter-section {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 40px;
    }

    .filter-btn {
      padding: 10px 25px;
      border-radius: 50px;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text-mid);
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s;
    }

    .filter-btn.active,
    .filter-btn:hover {
      background: var(--primary);
      color: #fff;
      border-color: var(--primary);
    }

    .event-link {
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .event-item {
      transition: 0.3s ease;
      cursor: pointer;
    }

    .event-item:hover {
      transform: translateY(-5px);
    }

    .sold-out {
      background: #777 !important;
    }

    .empty-event {
      grid-column: 1/-1;
      text-align: center;
      padding: 100px;
      color: #777;
    }

    .empty-event i {
      font-size: 50px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <?php include '../COMPONENTS/navbar.php'; ?>
  <?php include '../COMPONENTS/user_modals.php'; ?>

  <!-- ===== FEATURED EVENT ===== -->
  <div class="page-top">
    <?php if ($featured): ?>
      <section class="featured-event">
        <div class="featured-inner">
          <div class="featured-text">
            <p class="featured-date">
              Unggulan
            </p>
            <h1><?php echo htmlspecialchars($featured['judul_event']); ?></h1>
            <p class="featured-desc">
              <?php
              $deskripsi = htmlspecialchars($featured['deskripsi']);
              $max_length = 300;
              if (strlen($deskripsi) > $max_length) {
                $deskripsi = substr($deskripsi, 0, $max_length) . '...';
              }
              echo $deskripsi;
              ?>
            </p>
            <p class="featured-sub"><?php echo htmlspecialchars($featured['featured_sub'] ?: 'Jangan lewatkan kesempatan langka ini untuk menyaksikan pertunjukan seni budaya terbaik!'); ?></p>
            <div class="featured-meta">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <?php echo date('l, d F Y', strtotime($featured['tanggal_event'])); ?>
              </span>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                  <circle cx="9" cy="7" r="4" />
                </svg>
                <?php echo $featured['total_kursi']; ?> Kursi Tersedia
              </span>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <?php echo htmlspecialchars($featured['lokasi']); ?>
              </span>
              <span class="price-tag">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="12" y1="1" x2="12" y2="23" />
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
                <?php echo ($featured['harga'] > 0) ? 'Rp ' . number_format($featured['harga'], 0, ',', '.') : 'Gratis'; ?>
              </span>
            </div>
            <a href="#" class="btn-reserve" onclick="openBookingModal(<?php echo htmlspecialchars(json_encode($featured)); ?>)" style="display: inline-block; width: fit-content; margin-top: 20px; margin-left: 0;">Pesan Sekarang</a>
          </div>
          <div class="featured-img">
            <?php
            $imgPath = $featured['gambar1'] ?: '';
            if ($imgPath && !str_starts_with($imgPath, 'uploads/') && !str_starts_with($imgPath, 'images/')) {
              $imgPath = 'images/storage/' . $imgPath;
            } elseif ($imgPath && str_starts_with($imgPath, 'uploads/')) {
              $imgPath = $imgPath;
            }
            ?>
            <img src="../../<?php echo $imgPath ?: 'images/storage/default.png'; ?>" alt="<?php echo htmlspecialchars($featured['judul_event']); ?>">
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- ===== SEARCH BAR ===== -->
    <div class="search-bar-wrap">
      <div class="search-bar">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <circle cx="11" cy="11" r="8" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <input type="text" id="searchInput" placeholder="Search..." oninput="filterEvents()" />
      </div>
    </div>
  </div>

  <header class="page-header">
    <h1>Jelajahi Event Budaya</h1>
    <p>Temukan keajaiban tradisi Jawa di setiap sudut kota</p>
  </header>

  <div class="filter-section">
    <button class="filter-btn active" onclick="setCategory('all', this)">Semua</button>
    <?php foreach ($categories as $cat): ?>
      <button class="filter-btn" onclick="setCategory('<?php echo $cat['id']; ?>', this)">
        <?php echo htmlspecialchars($cat['nama_kategori']); ?>
      </button>
    <?php endforeach; ?>
  </div>

  <main class="event-container" id="eventList">

    <?php if (mysqli_num_rows($query_events) > 0): ?>

      <?php while ($ev = mysqli_fetch_assoc($query_events)): ?>

        <a href="detail.php?id=<?php echo $ev['id']; ?>" class="event-link">

          <div class="modern-card event-item"
            data-category="<?php echo $ev['kategori_id']; ?>"
            data-title="<?php echo strtolower(htmlspecialchars($ev['judul_event'])); ?>">

            <!-- SLIDER -->
            <div class="card-slider" id="slider-<?php echo $ev['id']; ?>">

              <?php
              $img1 = $ev['gambar1'] ?: '';
              $img2 = $ev['gambar2'] ?: '';
              $img3 = $ev['gambar3'] ?: '';
              $img1 = ($img1 && !str_starts_with($img1, 'uploads/') && !str_starts_with($img1, 'images/')) ? 'images/storage/' . $img1 : $img1;
              $img2 = ($img2 && !str_starts_with($img2, 'uploads/') && !str_starts_with($img2, 'images/')) ? 'images/storage/' . $img2 : $img2;
              $img3 = ($img3 && !str_starts_with($img3, 'uploads/') && !str_starts_with($img3, 'images/')) ? 'images/storage/' . $img3 : $img3;
              ?>
              <img src="../../<?php echo $img1 ?: 'images/storage/default.png'; ?>"
                class="slider-img"
                alt="">

              <?php if ($ev['gambar2']): ?>
                <img src="../../<?php echo $img2; ?>"
                  class="slider-img"
                  alt="">
              <?php endif; ?>

              <?php if ($ev['gambar3']): ?>
                <img src="../../<?php echo $img3; ?>"
                  class="slider-img"
                  alt="">
              <?php endif; ?>

            </div>

            <!-- DOT -->
            <div class="slider-dots">

              <div class="dot active"></div>

              <?php if ($ev['gambar2']): ?>
                <div class="dot"></div>
              <?php endif; ?>

              <?php if ($ev['gambar3']): ?>
                <div class="dot"></div>
              <?php endif; ?>

            </div>

            <!-- CONTENT -->
            <div class="card-overlay">

              <h2 class="card-title">
                <?php echo htmlspecialchars($ev['judul_event']); ?>
              </h2>

              <div class="card-location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo htmlspecialchars($ev['lokasi']); ?>
              </div>

              <div class="card-info-row">

                <div class="info-item">
                  <i class="fas fa-chair"></i>
                  <span>Total: <?php echo $ev['total_kursi']; ?></span>
                </div>

                <div class="info-item">
                  <i class="fas fa-user-clock"></i>
                  <span>Sisa: <?php echo $ev['sisa_kursi']; ?></span>
                </div>

                <div class="info-item">
                  <i class="fas fa-calendar-day"></i>
                  <span>
                    <?php echo date('d M', strtotime($ev['tanggal_event'])); ?>
                  </span>
                </div>

              </div>

              <div class="card-footer">

                <div class="card-price">
                  <?php
                  echo ($ev['harga'] > 0)
                    ? 'Rp ' . number_format($ev['harga'], 0, ',', '.')
                    : 'Gratis';
                  ?>
                </div>

                <?php if ($ev['sisa_kursi'] > 0): ?>
                  <div class="btn-reserve">
                    Lihat Detail
                  </div>
                <?php else: ?>
                  <div class="btn-reserve sold-out">
                    Tiket Habis
                  </div>
                <?php endif; ?>

              </div>

            </div>
          </div>

        </a>

      <?php endwhile; ?>

    <?php else: ?>

      <div class="empty-event">
        <i class="fas fa-calendar-times"></i>
        <p>Tidak ada event yang ditemukan.</p>
      </div>

    <?php endif; ?>

  </main>

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
          <input type="number" name="jumlah_tiket" id="bookingQty" min="1" value="1" oninput="calculateTotal()">
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

  <script>
    let currentCategory = 'all';

    function setCategory(catId, btn) {
      currentCategory = catId;

      // Update active button
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      filterEvents();
    }

    function filterEvents() {
      const searchTerm = document.getElementById('searchInput').value.toLowerCase();
      const items = document.querySelectorAll('.event-item');
      let foundCount = 0;

      items.forEach(item => {
        const title = item.getAttribute('data-title');
        const category = item.getAttribute('data-category');

        const matchesCategory = (currentCategory === 'all' || category === currentCategory);
        const matchesSearch = title.includes(searchTerm);

        if (matchesCategory && matchesSearch) {
          item.style.display = 'block';
          foundCount++;
        } else {
          item.style.display = 'none';
        }
      });

      // Show/hide empty state
      const emptyState = document.getElementById('emptyState');
      if (foundCount === 0) {
        if (!emptyState) {
          const main = document.getElementById('eventList');
          const div = document.createElement('div');
          div.id = 'emptyState';
          div.style.cssText = 'grid-column: 1/-1; text-align: center; padding: 100px; color: #777;';
          div.innerHTML = '<i class="fas fa-calendar-times" style="font-size: 50px; margin-bottom: 20px;"></i><p>Tidak ada event yang ditemukan.</p>';
          main.appendChild(div);
        } else {
          emptyState.style.display = 'block';
        }
      } else if (emptyState) {
        emptyState.style.display = 'none';
      }
    }

    // Simple Slider Logic
    document.querySelectorAll('.modern-card').forEach(card => {
      const slider = card.querySelector('.card-slider');
      const dots = card.querySelectorAll('.dot');
      let currentIdx = 0;
      const totalImgs = slider.querySelectorAll('.slider-img').length;

      if (totalImgs > 1) {
        setInterval(() => {
          currentIdx = (currentIdx + 1) % totalImgs;
          slider.style.transform = `translateX(-${currentIdx * 100}%)`;
          dots.forEach((dot, idx) => {
            dot.classList.toggle('active', idx === currentIdx);
          });
        }, 3000);
      }
    });

    // Booking Logic
    let selectedEventPrice = 0;
    const is_logged_in = <?php echo json_encode($is_logged_in); ?>;

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

      calculateTotal();
      document.getElementById('bookingModal').classList.add('show');
    }

    function closeBookingModal() {
      document.getElementById('bookingModal').classList.remove('show');
    }

    function calculateTotal() {
      const qty = document.getElementById('bookingQty').value || 0;
      const total = qty * selectedEventPrice;
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
            // Kita gunakan modal sukses yang sudah ada di halaman atau ganti ke showAlert
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
  <?php include '../COMPONENTS/footer.php'; ?>
</body>

</html>