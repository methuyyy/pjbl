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
    <title>Kelola Pengguna - Admin</title>
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

        <div class="content" id="page-pengguna">
            <div class="page-header">
                <div>
                    <div class="page-title">Kelola Pengguna</div>
                    <div class="page-sub">Lihat dan kelola akun anggota</div>
                </div>
                <button class="btn btn-primary" onclick="openAddUserModal()"><i class="fas fa-user-plus"></i> Tambah Pengguna</button>
            </div>
            <div class="card">
                <div class="table-wrap">
                    <table id="user-table">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Kota</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="user-list-body"></tbody>
                    </table>
                </div>
                <div class="pagination">
                    <div style="font-size:13px;color:var(--text-muted);" id="user-count-text"></div>
                    <div class="pagination-controls">
                        <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit User -->
        <div id="edit-user-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
            <div class="modal-box" style="max-width:500px; text-align:left;">
                <div class="modal-title" id="user-modal-title">Edit Pengguna</div>
                <form id="edit-user-form" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit-user-id">
                    <div class="form-group">
                        <label class="form-label">Foto Profil</label>
                        <div style="display:flex; gap:12px; align-items:center;">
                            <div id="edit-user-pic-preview" style="width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#8B2500,#D4A017); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:1.5rem;">
                                ?
                            </div>
                            <div style="flex:1;">
                                <input type="file" name="profile_pic" id="edit-user-pic" accept="image/*" style="display:none;" onchange="previewEditProfilePic(this)">
                                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('edit-user-pic').click()">
                                    <i class="fas fa-upload"></i> Ganti Foto
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Nama Depan</label>
                            <input type="text" name="first_name" id="edit-user-fn" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Belakang</label>
                            <input type="text" name="last_name" id="edit-user-ln" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit-user-email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru (Kosongkan jika tidak ingin ganti)</label>
                        <div class="pw-wrap" style="position: relative;">
                            <input type="password" name="new_password" id="edit-user-pw" class="form-control" placeholder="Masukkan password baru">
                            <button class="pw-toggle" type="button" onclick="togglePw('edit-user-pw')" style="position: absolute; right:12px; top:50%; transform: translateY(-50%); border:0; background:transparent; cursor:pointer;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" id="edit-user-phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kota</label>
                            <input type="text" name="city" id="edit-user-city" class="form-control">
                        </div>
                    </div>
                    <div class="modal-actions" style="display: flex; gap:12px; margin-top:24px;">
                        <button type="button" class="btn btn-outline" onclick="closeUserModal()">Batal</button>
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
                <h3 id="confirm-title" style="margin-bottom:10px; font-size:20px; font-weight:700;">Konfirmasi Tindakan</h3>
                <p id="confirm-message" style="color:#666; margin-bottom:30px; font-size:14px; line-height:1.6;">Apakah Anda yakin ingin melakukan tindakan ini?</p>
                <div class="modal-actions" style="display: flex; gap:12px;">
                    <button class="btn btn-outline" style="flex:1; padding:12px; border-radius:50px; font-weight:600;" onclick="closeConfirmModal()">Batal</button>
                    <button class="btn btn-danger" id="btn-confirm" style="flex:1; padding:12px; border-radius:50px; font-weight:600;">Ya, Lanjutkan</button>
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
        // Highlight current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'user.php') {
                    link.classList.add('active');
                }
            });
            loadUsers();
        });

        async function loadUsers() {
            try {
                const res = await fetch('../../../BACKEND/admin_users.php?action=list');
                const data = await res.json();
                if (data.status === 'success') {
                    renderUsers(data.data);
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderUsers(users) {
            const tbody = document.getElementById('user-list-body');
            const countText = document.getElementById('user-count-text');
            countText.textContent = `Menampilkan 1 - ${users.length} dari ${users.length} data`;
            tbody.innerHTML = users.map(user => {
                const fullName = `${user.first_name || ''} ${user.last_name || ''}`.trim();
                const initials = fullName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                let profileDisplay = '';
                if (user.profile_pic) {
                    profileDisplay = `<img src="../../../uploads/user/${user.profile_pic}" alt="Profile" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">`;
                } else {
                    profileDisplay = `<div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#8B2500,#D4A017);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;">${initials}</div>`;
                }
                return `
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                ${profileDisplay}
                <div>
                  <strong style="display:block;">${fullName}</strong>
                  <span style="font-size:12px;color:var(--text-muted);">@${(user.first_name || 'user').toLowerCase()}</span>
                </div>
              </div>
            </td>
            <td>${user.email}</td>
            <td>${user.phone || '-'}</td>
            <td>${user.city || '-'}</td>
            <td>${new Date(user.created_at).toLocaleDateString('id-ID')}</td>
            <td class="col-aksi">
              <button class="btn btn-outline btn-sm" onclick="openEditUserModal(${user.id})">Edit</button>
              <button class="btn btn-danger btn-sm" onclick="confirmDeleteUser(${user.id})">Hapus</button>
            </td>
          </tr>`;
            }).join('');
        }

        function previewEditProfilePic(input) {
            const preview = document.getElementById('edit-user-pic-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '';
                    preview.style.background = 'url(' + e.target.result + ') center/cover no-repeat';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        async function openEditUserModal(id) {
            try {
                const res = await fetch('../../../BACKEND/admin_users.php?action=get&id=' + id);
                const data = await res.json();
                if (data.status === 'success') {
                    const user = data.data;
                    document.getElementById('edit-user-id').value = user.id;
                    document.getElementById('edit-user-fn').value = user.first_name;
                    document.getElementById('edit-user-ln').value = user.last_name;
                    document.getElementById('edit-user-email').value = user.email;
                    document.getElementById('edit-user-phone').value = user.phone || '';
                    document.getElementById('edit-user-city').value = user.city || '';
                    document.getElementById('user-modal-title').textContent = 'Edit Pengguna';

                    // Update preview
                    const preview = document.getElementById('edit-user-pic-preview');
                    if (user.profile_pic) {
                        preview.innerHTML = '';
                        preview.style.background = 'url(../../../uploads/user/' + user.profile_pic + ') center/cover no-repeat';
                    } else {
                        const fullName = (user.first_name || '') + ' ' + (user.last_name || '');
                        const trimmedName = fullName.trim();
                        const initials = trimmedName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                        preview.innerHTML = initials || '?';
                        preview.style.background = 'linear-gradient(135deg,#8B2500,#D4A017)';
                    }

                    document.getElementById('edit-user-modal').style.display = 'flex';
                }
            } catch (err) {
                console.error(err);
            }
        }

        function openAddUserModal() {
            document.getElementById('edit-user-id').value = '';
            document.getElementById('edit-user-fn').value = '';
            document.getElementById('edit-user-ln').value = '';
            document.getElementById('edit-user-email').value = '';
            document.getElementById('edit-user-pw').value = '';
            document.getElementById('edit-user-phone').value = '';
            document.getElementById('edit-user-city').value = '';
            document.getElementById('user-modal-title').textContent = 'Tambah Pengguna';
            document.getElementById('edit-user-modal').style.display = 'flex';
        }

        function closeUserModal() {
            document.getElementById('edit-user-modal').style.display = 'none';
        }

        document.getElementById('edit-user-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            try {
                const res = await fetch('../../../BACKEND/admin_users.php?action=update', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    closeUserModal();
                    showSuccess('Pengguna berhasil diperbarui');
                    loadUsers();
                } else {
                    alert(data.message);
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan');
            }
        });

        let pendingDeleteId = null;

        function confirmDeleteUser(id) {
            pendingDeleteId = id;
            document.getElementById('confirm-title').textContent = 'Hapus Pengguna?';
            document.getElementById('confirm-message').textContent = 'Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.';
            document.getElementById('confirm-modal').style.display = 'flex';
            document.getElementById('btn-confirm').onclick = deleteUser;
        }

        async function deleteUser() {
            if (!pendingDeleteId) return;
            try {
                const res = await fetch(`../../../BACKEND/admin_users.php?action=delete&id=${pendingDeleteId}`);
                const data = await res.json();
                if (data.status === 'success') {
                    closeConfirmModal();
                    showSuccess('Pengguna berhasil dihapus');
                    loadUsers();
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

        function togglePw(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }
    </script>
</body>

</html>