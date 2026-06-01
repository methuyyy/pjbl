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
    <title>Kelola Event - Admin</title>
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

        .form-section {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            background: var(--cream);
            border-radius: 12px;
            padding: 20px;
        }

        .dynamic-list {
            margin-top: 16px;
            gap: 16px;
            display: flex;
            flex-direction: column;
        }

        .dynamic-item {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .dynamic-item .form-group {
            margin-bottom: 0;
            flex: 1;
        }

        .event-img-cell {
            width: 80px;
        }

        .event-thumb {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid var(--border);
        }

        .event-thumb:hover {
            transform: scale(1.05);
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #image-preview-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 4000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(8px);
        }

        #previewed-image {
            max-width: 90%;
            max-height: 90vh;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        #icon-picker-modal .modal-box {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        #icon-grid div {
            transition: all 0.2s;
        }

        #icon-grid div:hover {
            transform: scale(1.1);
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #icon-grid div:active {
            transform: scale(0.95);
        }
    </style>
</head>

<body>
    <?php include '../components/sidebar.php'; ?>

    <main class="main">
        <?php include '../components/topbar.php'; ?>

        <div class="content" id="page-event">
            <div class="page-header">
                <div>
                    <div class="page-title">Kelola Event</div>
                    <div class="page-sub">Kelola semua event budaya</div>
                </div>
                <button class="btn btn-primary" onclick="openAddEventModal()"><i class="fas fa-plus"></i> Tambah Event</button>
            </div>
            <div class="card">
                <div class="table-wrap">
                    <table id="event-table">
                        <thead>
                            <tr>
                                <th>Poster</th>
                                <th>Event</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="event-list-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Event -->
        <div id="event-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; overflow-y:auto; padding:20px;">
            <div class="modal-box" style="max-width:900px; width:100%; max-height:90vh; overflow-y:auto; text-align:left; background:#fff; border-radius:20px; padding:25px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <div class="modal-title" id="event-modal-title">Tambah Event</div>
                    <button type="button" id="auto-fill-btn" class="btn btn-outline" style="padding:8px 16px; font-size:14px; border-radius:10px;">✨ Isi Test Data</button>
                </div>
                <form id="event-form" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="event-id">
                    <input type="hidden" name="existing_gambar1" id="existing-gambar1">
                    <input type="hidden" name="existing_gambar2" id="existing-gambar2">
                    <input type="hidden" name="existing_gambar3" id="existing-gambar3">

                    <div class="form-group">
                        <label class="form-label">Judul Event</label>
                        <input type="text" name="judul_event" id="event-judul" class="form-control" required>
                    </div>

                    <div class="grid-2" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
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

                    <div class="grid-2" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal_event" id="event-tanggal" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" id="event-lokasi" class="form-control">
                        </div>
                    </div>

                    <div class="grid-2" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Total Kursi</label>
                            <input type="number" name="total_kursi" id="event-total-kursi" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sisa Kursi</label>
                            <input type="number" name="sisa_kursi" id="event-sisa-kursi" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Harga (IDR)</label>
                        <input type="number" name="harga" id="event-harga" class="form-control" placeholder="0 untuk Gratis">
                    </div>

                    <div class="grid-2" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
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
                        <div class="grid-3" style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px;">
                            <input type="file" name="gambar1" accept="image/*" class="form-control">
                            <input type="file" name="gambar2" accept="image/*" class="form-control">
                            <input type="file" name="gambar3" accept="image/*" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <label class="form-label" style="margin:0;">Deskripsi</label>
                            <button type="button" id="generate-desc-btn" class="btn btn-outline" style="padding:6px 12px; font-size:13px; border-radius:10px;">
                                <i class="fas fa-wand-magic-sparkles" style="margin-right:6px;"></i>Generate dengan AI
                            </button>
                        </div>
                        <textarea name="deskripsi" id="event-desc" class="form-control" rows="5"></textarea>
                    </div>

                    <!-- Benefit -->
                    <div class="form-section">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <label class="form-label" style="margin:0; font-weight:600; font-size:15px; color:var(--brown-deep);">
                                <i class="fas fa-gift" style="margin-right:8px; color:var(--accent-gold);"></i>Benefit Event
                            </label>
                            <button type="button" class="btn btn-outline" style="border-radius:12px; padding:8px 16px; font-weight:500;" onclick="addBenefit()">
                                <i class="fas fa-plus" style="margin-right:6px;"></i>Add Benefit
                            </button>
                        </div>
                        <div id="benefit-wrapper" class="dynamic-list"></div>
                    </div>

                    <!-- Rundown -->
                    <div class="form-section">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <label class="form-label" style="margin:0; font-weight:600; font-size:15px; color:var(--brown-deep);">
                                <i class="fas fa-clock" style="margin-right:8px; color:var(--accent-gold);"></i>Rundown Event
                            </label>
                            <button type="button" class="btn btn-outline" style="border-radius:12px; padding:8px 16px; font-weight:500;" onclick="addRundown()">
                                <i class="fas fa-plus" style="margin-right:6px;"></i>Add Rundown
                            </button>
                        </div>
                        <div id="rundown-wrapper" class="dynamic-list"></div>
                    </div>

                    <!-- Narasumber -->
                    <div class="form-section">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <label class="form-label" style="margin:0; font-weight:600; font-size:15px; color:var(--brown-deep);">
                                <i class="fas fa-user-tie" style="margin-right:8px; color:var(--accent-gold);"></i>Narasumber
                            </label>
                            <button type="button" class="btn btn-outline" style="border-radius:12px; padding:8px 16px; font-weight:500;" onclick="addSpeaker()">
                                <i class="fas fa-plus" style="margin-right:6px;"></i>Add Narasumber
                            </button>
                        </div>
                        <div id="speaker-wrapper" class="dynamic-list"></div>
                    </div>

                    <!-- FAQ -->
                    <div class="form-section">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <label class="form-label" style="margin:0; font-weight:600; font-size:15px; color:var(--brown-deep);">
                                <i class="fas fa-question-circle" style="margin-right:8px; color:var(--accent-gold);"></i>FAQ Event
                            </label>
                            <button type="button" class="btn btn-outline" style="border-radius:12px; padding:8px 16px; font-weight:500;" onclick="addFaq()">
                                <i class="fas fa-plus" style="margin-right:6px;"></i>Add FAQ
                            </button>
                        </div>
                        <div id="faq-wrapper" class="dynamic-list"></div>
                    </div>

                    <!-- Syarat dan Ketentuan -->
                    <div class="form-section">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <label class="form-label" style="margin:0; font-weight:600; font-size:15px; color:var(--brown-deep);">
                                <i class="fas fa-file-contract" style="margin-right:8px; color:var(--accent-gold);"></i>Syarat dan Ketentuan
                            </label>
                            <button type="button" class="btn btn-outline" style="border-radius:12px; padding:8px 16px; font-weight:500;" onclick="addTerm()">
                                <i class="fas fa-plus" style="margin-right:6px;"></i>Add Syarat
                            </button>
                        </div>
                        <div id="term-wrapper" class="dynamic-list"></div>
                    </div>

                    <!-- Lokasi Detail -->
                    <div class="form-section">
                        <div style="margin-bottom:12px;">
                            <label class="form-label" style="margin:0; font-weight:600; font-size:15px; color:var(--brown-deep);">
                                <i class="fas fa-map-marked-alt" style="margin-right:8px; color:var(--accent-gold);"></i>Detail Lokasi
                            </label>
                        </div>
                        <div class="grid-2" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div class="form-group">
                                <label class="form-label">Nama Tempat</label>
                                <input type="text" name="location_name" id="location-name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Google Maps</label>
                                <input type="text" name="location_maps" id="location-maps" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="location_address" id="location-address" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Catatan Lokasi</label>
                            <textarea name="location_note" id="location-note" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="modal-actions" style="display:flex; gap:12px; margin-top:24px;">
                        <button type="button" class="btn btn-outline" onclick="closeEventModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Preview Gambar -->
        <div id="image-preview-modal" onclick="closeImagePreview()">
            <img id="previewed-image" src="" alt="Preview">
        </div>

        <!-- Modal Icon Picker -->
        <div id="icon-picker-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2500; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
            <div class="modal-box" style="max-width:600px; width:90%; background:#fff; border-radius:20px; padding:25px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <div class="modal-title">Pilih Icon</div>
                    <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('icon-picker-modal').style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="icon-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(50px, 1fr)); gap:10px; max-height:400px; overflow-y:auto;"></div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div id="confirm-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:2500; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
            <div class="modal-box" style="background:#fff; padding:35px; border-radius:24px; max-width:420px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div id="confirm-icon-wrap" style="width:70px; height:70px; background:#fff3e0; color:#f57c00; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 id="confirm-title" style="margin-bottom:10px; font-size:20px; font-weight:700;">Konfirmasi Hapus</h3>
                <p id="confirm-message" style="color:#666; margin-bottom:30px; font-size:14px; line-height:1.6;">Apakah Anda yakin ingin menghapus event ini?</p>
                <div class="modal-actions" style="display: flex; gap:12px;">
                    <button class="btn btn-outline" style="flex:1; padding:12px; border-radius:50px; font-weight:600;" onclick="closeConfirmModal()">Batal</button>
                    <button class="btn btn-danger" id="btn-confirm" style="flex:1; padding:12px; border-radius:50px; font-weight:600;">Ya, Hapus</button>
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
        let categories = [];

        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'event.php') {
                    link.classList.add('active');
                }
            });

            // Populate icon picker grid
            const iconGrid = document.getElementById('icon-grid');
            iconList.forEach(icon => {
                const iconDiv = document.createElement('div');
                iconDiv.style.cssText = 'width:50px; height:50px; border:1px solid var(--border); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:24px; cursor:pointer; color:var(--primary); transition:all 0.2s;';
                iconDiv.innerHTML = `<i class="fas ${icon}"></i>`;
                iconDiv.onclick = () => selectIcon(icon);
                iconDiv.onmouseover = () => {
                    iconDiv.style.borderColor = 'var(--primary)';
                    iconDiv.style.background = 'var(--cream)';
                };
                iconDiv.onmouseout = () => {
                    iconDiv.style.borderColor = 'var(--border)';
                    iconDiv.style.background = 'transparent';
                };
                iconGrid.appendChild(iconDiv);
            });

            loadCategories();
            loadEvents();

            // Event listener untuk generate deskripsi dengan AI
            document.getElementById('generate-desc-btn').addEventListener('click', generateDescriptionWithAI);
        });

        async function generateDescriptionWithAI() {
            const judul = document.getElementById('event-judul').value;
            const kategoriId = document.getElementById('event-cat-id').value;
            const lokasi = document.getElementById('event-lokasi').value;

            if (!judul) {
                alert('Silakan isi judul event terlebih dahulu');
                return;
            }

            // Dapatkan nama kategori dari id yang dipilih
            let kategoriNama = '';
            if (kategoriId) {
                const kategori = categories.find(c => c.id == kategoriId);
                if (kategori) {
                    kategoriNama = kategori.nama_kategori;
                }
            }

            const btn = document.getElementById('generate-desc-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Generating...';
            btn.disabled = true;

            try {
                const res = await fetch('../../../BACKEND/gemini.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        judul: judul,
                        kategori: kategoriNama,
                        lokasi: lokasi
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    document.getElementById('event-desc').value = data.deskripsi;
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat memanggil AI');
            } finally {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        }

        async function loadCategories() {
            try {
                const res = await fetch('../../../BACKEND/admin_categories.php?action=list');
                const data = await res.json();
                if (data.status === 'success') {
                    categories = data.data;
                    const select = document.getElementById('event-cat-id');
                    select.innerHTML = categories.map(cat => `<option value="${cat.id}">${cat.nama_kategori}</option>`).join('');
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function loadEvents() {
            try {
                const res = await fetch('../../../BACKEND/admin_events.php?action=list');
                const data = await res.json();
                if (data.status === 'success') {
                    renderEvents(data.data);
                }
            } catch (err) {
                console.error(err);
            }
        }

        function getImageUrl(img) {
            if (!img) return 'https://via.placeholder.com/60x60?text=No+Image';
            if (img.startsWith('uploads/') || img.startsWith('images/')) return `../../../${img}`;
            return `../../../images/storage/${img}`;
        }

        function renderEvents(events) {
            const tbody = document.getElementById('event-list-body');
            tbody.innerHTML = events.map(event => {
                const statusClass = event.status === 'Aktif' ? 'success' : event.status === 'Mendatang' ? 'info' : 'muted';
                const imageUrl = getImageUrl(event.gambar1);
                return `
          <tr>
            <td class="event-img-cell">
              <img src="${imageUrl}" alt="Poster" class="event-thumb" onclick="openImagePreview('${imageUrl}')">
            </td>
            <td><strong>${event.judul_event}</strong></td>
            <td>${event.nama_kategori || '-'}</td>
            <td>${event.tanggal_event ? new Date(event.tanggal_event).toLocaleDateString('id-ID') : '-'}</td>
            <td>${event.lokasi || '-'}</td>
            <td>Rp ${parseInt(event.harga || 0).toLocaleString('id-ID')}</td>
            <td><span class="badge ${statusClass}">${event.status}</span></td>
            <td class="col-aksi">
              <button class="btn btn-outline btn-sm" onclick="openEditEventModal(${event.id})">Edit</button>
              <button class="btn btn-danger btn-sm" onclick="confirmDeleteEvent(${event.id})">Hapus</button>
            </td>
          </tr>`;
            }).join('');
        }

        function openAddEventModal() {
            document.getElementById('event-id').value = '';
            document.getElementById('event-form').reset();
            document.getElementById('benefit-wrapper').innerHTML = '';
            document.getElementById('rundown-wrapper').innerHTML = '';
            document.getElementById('speaker-wrapper').innerHTML = '';
            document.getElementById('faq-wrapper').innerHTML = '';
            document.getElementById('term-wrapper').innerHTML = '';
            document.getElementById('event-modal-title').textContent = 'Tambah Event';
            document.getElementById('event-modal').style.display = 'flex';
        }

        // Auto-fill function
        document.getElementById('auto-fill-btn').addEventListener('click', function() {
            // Fill basic fields
            document.getElementById('event-judul').value = '✨ Festival Budaya Nusantara ' + Math.floor(Math.random() * 100);
            document.getElementById('event-cat-id').value = 1; // Seni Pertunjukan
            document.getElementById('event-status').value = 'Aktif';
            document.getElementById('event-tanggal').value = '2026-12-31';
            document.getElementById('event-lokasi').value = 'Balai Budaya Kota Malang';
            document.getElementById('event-total-kursi').value = 250;
            document.getElementById('event-sisa-kursi').value = 200;
            document.getElementById('event-harga').value = 75000;
            document.getElementById('event-is-featured').checked = true;
            document.getElementById('event-featured-sub').value = 'Acara Budaya Paling Dinanti Tahun Ini!';
            document.getElementById('event-desc').value = 'Nikmati keindahan budaya nusantara dengan tari tradisional, musik gamelan, dan kuliner khas dari seluruh Indonesia!';

            // Fill location details
            document.getElementById('location-name').value = 'Balai Budaya Malang';
            document.getElementById('location-address').value = 'Jl. Ijen No. 123, Malang, Jawa Timur';
            document.getElementById('location-maps').value = 'https://goo.gl/maps/xyz123';
            document.getElementById('location-note').value = 'Parkir tersedia di sebelah gedung';

            // Add benefit
            addBenefit('fa-ticket-alt', 'Tiket Gratis Merchandise', 'Setiap pembeli tiket mendapatkan souvenir budaya');
            addBenefit('fa-utensils', 'Cicipi Kuliner Nusantara', 'Tersedia berbagai makanan khas dari seluruh Indonesia');
            addBenefit('fa-camera', 'Spot Foto Instagramable', 'Banyak spot foto dengan tema budaya');

            // Add rundown
            addRundown('09:00:00', 'Pembukaan Acara', 'Sambutan dan tari tradisional');
            addRundown('11:00:00', 'Workshop Batik', 'Belajar membuat batik bersama pengrajin');
            addRundown('14:00:00', 'Pertunjukan Gamelan', 'Live music gamelan tradisional');
            addRundown('17:00:00', 'Penutupan', 'Pengumuman doorprize dan foto bersama');

            // Add speaker
            addSpeaker('Kang Maman', 'Pengamat Budaya', 'Kang Maman adalah pengamat budaya yang telah menggeluti dunia seni selama 20 tahun');

            // Add FAQ
            addFaq('Apakah ada diskon?', 'Ya, diskon 20% untuk pembelian 5 tiket atau lebih');
            addFaq('Boleh bawa anak kecil?', 'Tentu saja! Anak di bawah 5 tahun gratis');

            // Add terms
            addTerm('Tiket yang sudah dibeli tidak dapat dikembalikan');
            addTerm('Waktu acara dapat berubah sesuai kondisi');
            addTerm('Peserta diwajibkan mematuhi protokol kesehatan');
        });

        async function openEditEventModal(id) {
            console.log('Opening edit modal for event ID:', id);
            try {
                const res = await fetch(`../../../BACKEND/admin_events.php?action=get&id=${id}`);
                console.log('Response status:', res.status);
                const data = await res.json();
                console.log('Data received:', data);
                if (data.status === 'success') {
                    const eventData = data.data;
                    const event = eventData.event;

                    document.getElementById('event-id').value = event.id;
                    document.getElementById('event-judul').value = event.judul_event || '';
                    document.getElementById('event-cat-id').value = event.kategori_id || '';
                    document.getElementById('event-status').value = event.status || 'Aktif';
                    document.getElementById('event-tanggal').value = event.tanggal_event || '';
                    document.getElementById('event-lokasi').value = event.lokasi || '';
                    document.getElementById('event-total-kursi').value = event.total_kursi || '';
                    document.getElementById('event-sisa-kursi').value = event.sisa_kursi || '';
                    document.getElementById('event-harga').value = event.harga || '';
                    document.getElementById('event-is-featured').checked = event.is_featured == 1;
                    document.getElementById('event-featured-sub').value = event.featured_sub || '';
                    document.getElementById('event-desc').value = event.deskripsi || '';

                    document.getElementById('existing-gambar1').value = event.gambar1 || '';
                    document.getElementById('existing-gambar2').value = event.gambar2 || '';
                    document.getElementById('existing-gambar3').value = event.gambar3 || '';

                    // Populate dynamic fields
                    const benefitWrapper = document.getElementById('benefit-wrapper');
                    benefitWrapper.innerHTML = '';
                    (eventData.benefits || []).forEach(b => {
                        addBenefit(b.icon, b.title, b.description);
                    });

                    const rundownWrapper = document.getElementById('rundown-wrapper');
                    rundownWrapper.innerHTML = '';
                    (eventData.rundowns || []).forEach(r => {
                        addRundown(r.jam_mulai, r.title, r.description);
                    });

                    const speakerWrapper = document.getElementById('speaker-wrapper');
                    speakerWrapper.innerHTML = '';
                    (eventData.speakers || []).forEach(s => {
                        addSpeaker(s.nama, s.jabatan, s.bio);
                    });

                    const faqWrapper = document.getElementById('faq-wrapper');
                    faqWrapper.innerHTML = '';
                    (eventData.faqs || []).forEach(f => {
                        addFaq(f.question, f.answer);
                    });

                    const termWrapper = document.getElementById('term-wrapper');
                    termWrapper.innerHTML = '';
                    (eventData.terms || []).forEach(t => {
                        addTerm(t.isi);
                    });

                    // Location
                    const loc = eventData.location || {};
                    document.getElementById('location-name').value = loc.nama_tempat || '';
                    document.getElementById('location-address').value = loc.alamat || '';
                    document.getElementById('location-maps').value = loc.maps_link || '';
                    document.getElementById('location-note').value = loc.catatan || '';

                    document.getElementById('event-modal-title').textContent = 'Edit Event';
                    document.getElementById('event-modal').style.display = 'flex';
                } else {
                    alert('Error: ' + (data.message || 'Gagal memuat event'));
                }
            } catch (err) {
                console.error('Error opening edit modal:', err);
                alert('Terjadi kesalahan saat memuat event');
            }
        }

        function closeEventModal() {
            document.getElementById('event-modal').style.display = 'none';
        }

        document.getElementById('event-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            const action = id ? 'update' : 'add';

            try {
                const res = await fetch(`../../../BACKEND/admin_events.php?action=${action}`, {
                    method: 'POST',
                    body: formData
                });
                const text = await res.text();
                try {
                    const data = JSON.parse(text);
                    if (data.status === 'success') {
                        closeEventModal();
                        showSuccess(`Event berhasil ${id ? 'diperbarui' : 'ditambahkan'}`);
                        loadEvents();
                    } else {
                        alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                    }
                } catch (parseErr) {
                    console.error('Response not JSON:', text);
                    alert('Server error: ' + text);
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan');
            }
        });

        let pendingDeleteId = null;

        function confirmDeleteEvent(id) {
            pendingDeleteId = id;
            document.getElementById('confirm-modal').style.display = 'flex';
            document.getElementById('btn-confirm').onclick = deleteEvent;
        }

        async function deleteEvent() {
            if (!pendingDeleteId) return;
            try {
                const res = await fetch(`../../../BACKEND/admin_events.php?action=delete&id=${pendingDeleteId}`);
                const data = await res.json();
                if (data.status === 'success') {
                    closeConfirmModal();
                    showSuccess('Event berhasil dihapus');
                    loadEvents();
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

        function openImagePreview(url) {
            document.getElementById('previewed-image').src = url;
            document.getElementById('image-preview-modal').style.display = 'flex';
        }

        function closeImagePreview() {
            document.getElementById('image-preview-modal').style.display = 'none';
        }

        // Dynamic fields functions
        let activeIconInput = null;

        const iconList = [
            'fa-star', 'fa-heart', 'fa-check', 'fa-gift', 'fa-tag',
            'fa-music', 'fa-video', 'fa-camera', 'fa-image', 'fa-palette',
            'fa-utensils', 'fa-coffee', 'fa-glass-cheers', 'fa-leaf', 'fa-seedling',
            'fa-dumbbell', 'fa-bicycle', 'fa-swimming-pool', 'fa-plane', 'fa-map-marked-alt',
            'fa-book', 'fa-graduation-cap', 'fa-lightbulb', 'fa-cog', 'fa-toolbox',
            'fa-laptop', 'fa-mobile-alt', 'fa-phone', 'fa-envelope', 'fa-comments',
            'fa-calendar', 'fa-clock', 'fa-calendar-check', 'fa-ticket-alt', 'fa-shopping-cart',
            'fa-users', 'fa-user', 'fa-user-friends', 'fa-user-tie', 'fa-user-cog',
            'fa-building', 'fa-home', 'fa-hotel', 'fa-store', 'fa-landmark',
            'fa-truck', 'fa-car', 'fa-bus', 'fa-train', 'fa-ship',
            'fa-bolt', 'fa-fire', 'fa-water', 'fa-wind', 'fa-sun',
            'fa-moon', 'fa-cloud', 'fa-cloud-rain', 'fa-snowflake', 'fa-thunderstorm'
        ];

        function openIconPicker(inputElement) {
            activeIconInput = inputElement;
            document.getElementById('icon-picker-modal').style.display = 'flex';
        }

        function selectIcon(iconClass) {
            if (activeIconInput) {
                activeIconInput.value = iconClass;
                activeIconInput.previousElementSibling.innerHTML = `<i class="fas ${iconClass}"></i>`;
            }
            document.getElementById('icon-picker-modal').style.display = 'none';
        }

        function addBenefit(icon = '', title = '', desc = '') {
            const wrapper = document.getElementById('benefit-wrapper');
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <div class="form-group">
          <label class="form-label">Icon</label>
          <div style="display:flex; gap:8px; align-items:center;">
            <div style="width:40px; height:40px; border:1px solid var(--border); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; cursor:pointer; color:var(--primary); transition:all 0.2s;" onclick="openIconPicker(this.nextElementSibling)" onmouseover="this.style.borderColor='var(--primary)'; this.style.background='var(--cream)'" onmouseout="this.style.borderColor='var(--border)'; this.style.background='transparent'">
              <i class="fas ${icon || 'fa-star'}"></i>
            </div>
            <input type="text" name="benefit_icon[]" class="form-control" value="${icon}" placeholder="fa-star" readonly style="cursor:pointer;" onclick="openIconPicker(this)">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Judul</label>
          <input type="text" name="benefit_title[]" class="form-control" value="${title}">
        </div>
        <div class="form-group" style="flex:2;">
          <label class="form-label">Deskripsi</label>
          <input type="text" name="benefit_desc[]" class="form-control" value="${desc}">
        </div>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="height:fit-content; margin-top:24px; border-radius:10px;"><i class="fas fa-trash"></i></button>
      `;
            wrapper.appendChild(item);
        }

        function addRundown(waktu = '', title = '', desc = '') {
            const wrapper = document.getElementById('rundown-wrapper');
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <div class="form-group">
          <label class="form-label">Waktu</label>
          <input type="time" name="rundown_waktu[]" class="form-control" value="${waktu}">
        </div>
        <div class="form-group">
          <label class="form-label">Judul</label>
          <input type="text" name="rundown_title[]" class="form-control" value="${title}">
        </div>
        <div class="form-group" style="flex:2;">
          <label class="form-label">Deskripsi</label>
          <input type="text" name="rundown_desc[]" class="form-control" value="${desc}">
        </div>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="height:fit-content; margin-top:24px; border-radius:10px;"><i class="fas fa-trash"></i></button>
      `;
            wrapper.appendChild(item);
        }

        function addSpeaker(name = '', job = '', bio = '') {
            const wrapper = document.getElementById('speaker-wrapper');
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <div style="flex:1; display:flex; flex-direction:column; gap:12px;">
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Nama</label>
            <input type="text" name="speaker_name[]" class="form-control" value="${name}">
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Jabatan</label>
            <input type="text" name="speaker_job[]" class="form-control" value="${job}">
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Bio</label>
            <textarea name="speaker_bio[]" class="form-control" rows="2">${bio}</textarea>
          </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="height:fit-content; margin-top:24px; border-radius:10px;"><i class="fas fa-trash"></i></button>
      `;
            wrapper.appendChild(item);
        }

        function addFaq(question = '', answer = '') {
            const wrapper = document.getElementById('faq-wrapper');
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <div style="flex:1; display:flex; flex-direction:column; gap:12px;">
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Pertanyaan</label>
            <input type="text" name="faq_question[]" class="form-control" value="${question}">
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Jawaban</label>
            <textarea name="faq_answer[]" class="form-control" rows="2">${answer}</textarea>
          </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="height:fit-content; margin-top:24px; border-radius:10px;"><i class="fas fa-trash"></i></button>
      `;
            wrapper.appendChild(item);
        }

        function addTerm(text = '') {
            const wrapper = document.getElementById('term-wrapper');
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            item.innerHTML = `
        <div class="form-group" style="flex:1;">
          <label class="form-label">Syarat</label>
          <textarea name="term_text[]" class="form-control" rows="1">${text}</textarea>
        </div>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="height:fit-content; margin-top:24px; border-radius:10px;"><i class="fas fa-trash"></i></button>
      `;
            wrapper.appendChild(item);
        }
    </script>
</body>

</html>