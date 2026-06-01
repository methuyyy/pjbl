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
    <title>Pesan Masuk - Admin</title>
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

        <div class="content" id="page-pesan">
            <div class="page-header">
                <div>
                    <div class="page-title">Pesan Masuk</div>
                    <div class="page-sub">Kelola semua pesan dari pengguna</div>
                </div>
            </div>
            <div class="card">
                <div class="table-wrap">
                    <table id="message-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Pesan</th>
                                <th>Balasan</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="message-list-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Balas Pesan -->
        <div id="reply-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
            <div class="modal-box" style="max-width:600px; text-align:left;">
                <div class="modal-title">Balas Pesan</div>
                <div id="original-message" style="margin-bottom:20px; padding:15px; background:#f5f5f5; border-radius:8px; font-size:14px;"></div>
                <form id="reply-form">
                    <input type="hidden" name="message_id" id="reply-msg-id">
                    <div class="form-group">
                        <label class="form-label">Tulis Balasan</label>
                        <textarea name="balasan" id="reply-text" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="modal-actions" style="display:flex; gap:12px; margin-top:24px;">
                        <button type="button" class="btn btn-outline" onclick="closeReplyModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Balasan</button>
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
                <p id="confirm-message" style="color:#666; margin-bottom:30px; font-size:14px; line-height:1.6;">Apakah Anda yakin ingin menghapus pesan ini?</p>
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
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'pesan.php') {
                    link.classList.add('active');
                }
            });
            loadMessages();
        });

        async function loadMessages() {
            try {
                const res = await fetch('../../../BACKEND/admin_messages.php?action=list');
                const data = await res.json();
                if (data.status === 'success') {
                    renderMessages(data.data);
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderMessages(messages) {
            const tbody = document.getElementById('message-list-body');
            tbody.innerHTML = messages.map(msg => {
                const statusClass = msg.status === 'Dibalas' ? 'success' : 'warning';
                return `
          <tr>
            <td>${msg.nama}</td>
            <td>${msg.email}</td>
            <td><strong>${msg.subjek}</strong></td>
            <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${msg.pesan}</td>
            <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--text-muted);">${msg.reply_text || '-'}</td>
            <td><span class="badge ${statusClass}">${msg.status}</span></td>
            <td>${new Date(msg.created_at).toLocaleDateString('id-ID')}</td>
            <td class="col-aksi">
              ${msg.status !== 'Dibalas' ? `
                <button class="btn btn-outline btn-sm" onclick="openReplyModal(${msg.id}, '${msg.pesan.replace(/'/g, "\\'")}')">Balas</button>
              ` : ''}
              <button class="btn btn-danger btn-sm" onclick="confirmDeleteMessage(${msg.id})">Hapus</button>
            </td>
          </tr>`;
            }).join('');
        }

        function openReplyModal(id, originalText) {
            document.getElementById('reply-msg-id').value = id;
            document.getElementById('original-message').innerHTML = `<strong>Pesan Asli:</strong><br>${originalText}`;
            document.getElementById('reply-text').value = '';
            document.getElementById('reply-modal').style.display = 'flex';
        }

        function closeReplyModal() {
            document.getElementById('reply-modal').style.display = 'none';
        }

        document.getElementById('reply-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const res = await fetch('../../../BACKEND/admin_messages.php?action=reply', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    closeReplyModal();
                    showSuccess('Balasan berhasil dikirim');
                    loadMessages();
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan');
            }
        });

        let pendingDeleteId = null;

        function confirmDeleteMessage(id) {
            pendingDeleteId = id;
            document.getElementById('confirm-modal').style.display = 'flex';
            document.getElementById('btn-confirm').onclick = deleteMessage;
        }

        async function deleteMessage() {
            if (!pendingDeleteId) return;
            try {
                const res = await fetch(`../../../BACKEND/admin_messages.php?action=delete&id=${pendingDeleteId}`);
                const data = await res.json();
                if (data.status === 'success') {
                    closeConfirmModal();
                    showSuccess('Pesan berhasil dihapus');
                    loadMessages();
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