<!-- Custom User Modals -->
<div id="user-alert-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div style="background:#fff; padding:35px; border-radius:24px; max-width:400px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
        <div id="user-alert-icon" style="width:70px; height:70px; background:#f9f5f0; color:#8b2500; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px;">
            <i class="fas fa-info-circle"></i>
        </div>
        <h3 id="user-alert-title" style="margin-bottom:10px; font-size:20px; font-weight:700; font-family: 'Poppins', sans-serif;">Pemberitahuan</h3>
        <p id="user-alert-message" style="color:#666; margin-bottom:30px; font-size:14px; line-height:1.6; font-family: 'Poppins', sans-serif;">Pesan alert di sini.</p>
        <button onclick="closeUserAlert()" style="width:100%; padding:14px; background:#8b2500; color:#fff; border:none; border-radius:50px; font-weight:700; cursor:pointer; font-family: 'Poppins', sans-serif;">Oke, Mengerti</button>
    </div>
</div>

<div id="user-confirm-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9998; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div style="background:#fff; padding:35px; border-radius:24px; max-width:420px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
        <div id="user-confirm-icon" style="width:70px; height:70px; background:#fff3e0; color:#f57c00; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 id="user-confirm-title" style="margin-bottom:10px; font-size:20px; font-weight:700; font-family: 'Poppins', sans-serif;">Konfirmasi</h3>
        <p id="user-confirm-message" style="color:#666; margin-bottom:30px; font-size:14px; line-height:1.6; font-family: 'Poppins', sans-serif;">Apakah Anda yakin?</p>
        <div style="display:flex; gap:12px;">
            <button onclick="closeUserConfirm(false)" style="flex:1; padding:12px; background:transparent; border:1px solid #ddd; color:#666; border-radius:50px; font-weight:600; cursor:pointer; font-family: 'Poppins', sans-serif;">Batal</button>
            <button id="user-confirm-yes" style="flex:1; padding:12px; background:#8b2500; color:#fff; border:none; border-radius:50px; font-weight:600; cursor:pointer; font-family: 'Poppins', sans-serif;">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
let confirmCallback = null;

function showAlert(message, title = 'Pemberitahuan', type = 'info') {
    document.getElementById('user-alert-title').innerText = title;
    document.getElementById('user-alert-message').innerText = message;
    
    const iconWrap = document.getElementById('user-alert-icon');
    if (type === 'success') {
        iconWrap.style.background = '#e8f5e9';
        iconWrap.style.color = '#2e7d32';
        iconWrap.innerHTML = '<i class="fas fa-check-circle"></i>';
    } else if (type === 'error') {
        iconWrap.style.background = '#ffebee';
        iconWrap.style.color = '#c62828';
        iconWrap.innerHTML = '<i class="fas fa-times-circle"></i>';
    } else {
        iconWrap.style.background = '#f9f5f0';
        iconWrap.style.color = '#8b2500';
        iconWrap.innerHTML = '<i class="fas fa-info-circle"></i>';
    }
    
    document.getElementById('user-alert-modal').style.display = 'flex';
}

function closeUserAlert() {
    document.getElementById('user-alert-modal').style.display = 'none';
}

function showConfirm(message, callback, title = 'Konfirmasi') {
    document.getElementById('user-confirm-title').innerText = title;
    document.getElementById('user-confirm-message').innerText = message;
    confirmCallback = callback;
    document.getElementById('user-confirm-modal').style.display = 'flex';
}

function closeUserConfirm(result) {
    document.getElementById('user-confirm-modal').style.display = 'none';
    if (result && confirmCallback) {
        confirmCallback();
    }
    confirmCallback = null;
}

document.getElementById('user-confirm-yes').onclick = function() {
    closeUserConfirm(true);
};
</script>
