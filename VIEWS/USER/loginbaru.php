<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk · Pawerti</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --clay:    #432525;
  --clay2:   #5A3232;
  --clay3:   #6E3E3E;
  --cream:   #FAF6F3;
  --warm1:   #F2EAE4;
  --warm2:   #E2D3C8;
  --ink:     #2C1A1A;
  --muted:   #896060;
  --border:  #D9C8C0;
  --shadow:  rgba(67,37,37,0.13);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  min-height: 100vh;
  font-family: 'Poppins', sans-serif;
  background: var(--cream);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
}

/* ── BACKGROUND ── */
.bg {
  position: fixed; inset: 0;
  background: var(--cream);
  z-index: 0;
}
.bg::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    radial-gradient(circle at 20% 20%, rgba(67,37,37,0.06) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(201,169,110,0.07) 0%, transparent 50%),
    radial-gradient(circle at 60% 10%, rgba(67,37,37,0.04) 0%, transparent 40%);
}
.bg-pattern {
  position: absolute; inset: 0;
  opacity: 0.05;
  background-image:
    repeating-linear-gradient(45deg, var(--clay) 0, var(--clay) 1px, transparent 0, transparent 50%),
    repeating-linear-gradient(-45deg, var(--clay) 0, var(--clay) 1px, transparent 0, transparent 50%);
  background-size: 30px 30px;
}

/* ── WRAPPER ── */
.wrapper {
  position: relative;
  z-index: 10;
  width: 100%;
  max-width: 460px;
  padding: 20px 16px;
  animation: fadeUp 0.7s cubic-bezier(.22,1,.36,1) both;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(28px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── LOGO ── */
.logo-area {
  text-align: center;
  margin-bottom: 28px;
}
.logo-mark {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 56px; height: 56px;
  border-radius: 50px;
  background: var(--clay);
  position: relative;
  margin-bottom: 12px;
  box-shadow: 0 4px 24px rgba(67,37,37,0.25);
}
.logo-mark::after {
  content: '';
  position: absolute;
  inset: 4px;
  border-radius: 50px;
  border: 1.5px solid rgba(255,255,255,0.2);
}
.logo-mark img {
  width: 50px; height: 50px;
  object-fit: contain;
  position: relative; z-index: 1;
}
.logo-name {
  font-family: 'Poppins', sans-serif;
  font-size: 22px;
  font-weight: 600;
  color: var(--ink);
  letter-spacing: -0.3px;
  display: block;
}
.logo-tagline {
  font-size: 11px;
  color: var(--muted);
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-top: 2px;
  display: block;
  font-weight: 400;
}

/* ── CARD ── */
.card {
  background: #fff;
  border-radius: 24px;
  border: 1px solid var(--border);
  overflow: hidden;
  box-shadow:
    0 2px 4px var(--shadow),
    0 12px 40px rgba(67,37,37,0.08),
    0 0 0 1px rgba(255,255,255,0.8) inset;
}

/* ── TABS ── */
.tabs {
  display: flex;
  background: var(--warm1);
  padding: 6px;
  gap: 4px;
  border-bottom: 1px solid var(--border);
}
.tab {
  flex: 1;
  padding: 10px;
  border: none;
  background: transparent;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 400;
  color: var(--muted);
  cursor: pointer;
  border-radius: 12px;
  transition: all 0.25s cubic-bezier(.22,1,.36,1);
}
.tab:hover { color: var(--clay); background: rgba(67,37,37,0.05); }
.tab.active {
  background: #fff;
  color: var(--clay);
  font-weight: 500;
  box-shadow: 0 2px 8px var(--shadow), 0 0 0 1px var(--border);
}

/* ── PANELS ── */
.panels { position: relative; }
.panel {
  padding: 32px 36px 36px;
  display: none;
  animation: panelIn 0.35s cubic-bezier(.22,1,.36,1) both;
}
.panel.active { display: block; }
@keyframes panelIn {
  from { opacity: 0; transform: translateX(14px); }
  to   { opacity: 1; transform: translateX(0); }
}

.panel-title {
  font-family: 'Poppins', sans-serif;
  font-size: 20px;
  font-weight: 600;
  color: var(--ink);
  margin-bottom: 4px;
  line-height: 1.3;
}
.panel-sub {
  font-size: 12.5px;
  color: var(--muted);
  margin-bottom: 24px;
  line-height: 1.5;
  font-weight: 400;
}

/* ── FORM & FIELD ── */
form { display: contents; }

.field { margin-bottom: 16px; }

.field-label {
  display: block;
  font-size: 11px;
  font-weight: 500;
  color: var(--ink);
  margin-bottom: 7px;
  letter-spacing: 0.6px;
  text-transform: uppercase;
}

.input-wrap { position: relative; }

.input-icon {
  position: absolute;
  left: 14px; top: 50%;
  transform: translateY(-50%);
  color: var(--muted);
  display: flex;
  align-items: center;
  pointer-events: none;
  transition: color .2s;
}
.input-icon svg { width: 16px; height: 16px; }

input[type="text"],
input[type="email"],
input[type="password"],
input[type="tel"] {
  width: 100%;
  padding: 13px 14px 13px 42px;
  border: 1.5px solid var(--border);
  border-radius: 12px;
  font-family: 'Poppins', sans-serif;
  font-size: 13.5px;
  color: var(--ink);
  background: var(--cream);
  outline: none;
  transition: all 0.2s;
  -webkit-appearance: none;
}
input:focus {
  border-color: var(--clay3);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(67,37,37,0.10);
}
.input-wrap:focus-within .input-icon { color: var(--clay3); }

.pw-toggle {
  position: absolute;
  right: 12px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none;
  cursor: pointer; color: var(--muted);
  padding: 4px;
  transition: color .2s;
  display: flex; align-items: center;
}
.pw-toggle:hover { color: var(--clay); }
.pw-toggle svg { width: 16px; height: 16px; }

/* Name row */
.name-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

/* Strength meter */
.strength-wrap { margin-top: 8px; display: none; }
.strength-bars { display: flex; gap: 4px; margin-bottom: 5px; }
.s-bar {
  flex: 1; height: 3px; border-radius: 99px;
  background: var(--warm2);
  transition: background 0.3s;
}
.s-bar.fill-weak   { background: #E53935; }
.s-bar.fill-fair   { background: #FB8C00; }
.s-bar.fill-good   { background: #FDD835; }
.s-bar.fill-strong { background: #43A047; }
.strength-label { font-size: 11px; color: var(--muted); }

/* Error */
.field-error {
  font-size: 11px;
  color: #C62828;
  margin-top: 5px;
  display: none;
  animation: errIn .2s ease;
}
@keyframes errIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:translateY(0); } }
.field-error.show { display: block; }

/* Agreement */
.agree-row {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 20px;
}
.agree-check {
  width: 17px; height: 17px;
  accent-color: var(--clay);
  margin-top: 2px;
  flex-shrink: 0;
  cursor: pointer;
}
.agree-text {
  font-size: 12px;
  color: var(--muted);
  line-height: 1.5;
}
.agree-text a { color: var(--clay); text-decoration: none; }
.agree-text a:hover { text-decoration: underline; }

/* Forgot */
.forgot-row {
  display: flex;
  justify-content: flex-end;
  margin-top: -8px;
  margin-bottom: 20px;
}
.forgot-link {
  font-size: 12px;
  color: var(--clay);
  text-decoration: none;
  transition: opacity .2s;
}
.forgot-link:hover { opacity: .7; }

/* Submit button */
.btn-submit {
  width: 100%;
  padding: 13px;
  background: var(--clay);
  color: #fff;
  border: none;
  border-radius: 12px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  letter-spacing: 0.2px;
  position: relative;
  overflow: hidden;
  transition: all 0.25s;
  box-shadow: 0 4px 16px rgba(67,37,37,0.28);
}
.btn-submit::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, transparent 60%);
}
.btn-submit:hover {
  background: var(--clay2);
  transform: translateY(-1px);
  box-shadow: 0 6px 24px rgba(67,37,37,0.33);
}
.btn-submit:active { transform: translateY(0); box-shadow: 0 2px 8px rgba(67,37,37,0.22); }
.btn-submit .btn-text { position: relative; z-index: 1; }
.btn-submit .btn-spinner { position: relative; z-index: 1; display: none; }
.btn-submit.loading .btn-text { display: none; }
.btn-submit.loading .btn-spinner { display: inline-block; }
.spinner {
  width: 18px; height: 18px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin .7s linear infinite;
  display: inline-block;
  vertical-align: middle;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Divider */
.divider {
  display: flex; align-items: center; gap: 12px;
  margin: 18px 0;
}
.divider-line { flex: 1; height: 1px; background: var(--border); }
.divider-text { font-size: 11px; color: var(--muted); letter-spacing: 0.4px; }

/* Social buttons */
.social-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.btn-social {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 11px;
  border: 1.5px solid var(--border);
  border-radius: 12px;
  background: #fff;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 400;
  color: var(--ink);
  cursor: pointer;
  transition: all .2s;
}
.btn-social:hover { border-color: var(--clay3); background: var(--cream); }

/* Footer switch */
.switch-text {
  text-align: center;
  font-size: 12.5px;
  color: var(--muted);
  margin-top: 18px;
}
.switch-link {
  color: var(--clay);
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  background: none; border: none;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  transition: opacity .2s;
}
.switch-link:hover { opacity: .7; }

/* Alert */
.alert {
  display: none;
  padding: 12px 14px;
  border-radius: 10px;
  font-size: 12.5px;
  margin-bottom: 18px;
  animation: errIn .2s ease;
  align-items: center;
  gap: 10px;
}
.alert.show { display: flex; }
.alert-error {
  background: #FFEBEE;
  color: #B71C1C;
  border: 1px solid #EF9A9A;
}

/* Steps indicator */
.steps-indicator {
  display: flex;
  align-items: center;
  margin-bottom: 24px;
}
.step-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 5px;
  flex: 1;
  position: relative;
}
.step-item:not(:last-child)::after {
  content: '';
  position: absolute;
  top: 13px; left: 50%;
  width: 100%;
  height: 1.5px;
  background: var(--border);
  z-index: 0;
  transition: background .3s;
}
.step-item.done:not(:last-child)::after { background: var(--clay); }
.step-dot {
  width: 26px; height: 26px;
  border-radius: 50%;
  background: var(--warm1);
  border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 500; color: var(--muted);
  z-index: 1;
  transition: all .3s;
  font-family: 'Poppins', sans-serif;
}
.step-item.active .step-dot {
  background: var(--clay);
  border-color: var(--clay);
  color: #fff;
  box-shadow: 0 0 0 4px rgba(67,37,37,0.10);
}
.step-item.done .step-dot {
  background: var(--clay);
  border-color: var(--clay);
  color: #fff;
}
.step-label { font-size: 10px; color: var(--muted); letter-spacing: 0.3px; }
.step-item.active .step-label { color: var(--clay); font-weight: 500; }

/* Page footer */
.page-footer {
  text-align: center;
  margin-top: 18px;
  font-size: 11px;
  color: var(--muted);
  letter-spacing: 0.3px;
}

/* Avatar upload */
.avatar-upload {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 20px;
}
.avatar-preview {
  width: 58px; height: 58px;
  border-radius: 50%;
  background: var(--warm1);
  border: 2px dashed var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: border-color .2s;
  flex-shrink: 0;
  color: var(--muted);
}
.avatar-preview:hover { border-color: var(--clay3); }
.avatar-preview svg { width: 22px; height: 22px; }
.avatar-upload-info { font-size: 12px; color: var(--muted); line-height: 1.5; }
.avatar-upload-btn {
  font-size: 12px; color: var(--clay); cursor: pointer;
  background: none; border: none; font-family: 'Poppins', sans-serif; padding: 0;
  font-weight: 500;
}

@keyframes popIn {
  from { transform: scale(0.5); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}

/* Success icon */
.success-icon {
  width: 64px; height: 64px;
  background: #E8F5E9;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 20px;
  animation: popIn .5s cubic-bezier(.22,1,.36,1) both;
  color: #2E7D32;
}
.success-icon svg { width: 32px; height: 32px; }

/* Responsive */
@media (max-width: 480px) {
  .panel { padding: 24px 20px 28px; }
  .name-row { grid-template-columns: 1fr; }
  .social-row { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="bg"><div class="bg-pattern"></div></div>

<div class="wrapper">
  <!-- Logo -->
  <div class="logo-area">
    <div class="logo-mark">
      <img src="../../images/logobg.png" alt="Logo Pawerti">
    </div>
    <span class="logo-name">Pawerti</span>
    <span class="logo-tagline">Jelajahi Kekayaan Budaya Jawa</span>
  </div>

  <!-- Card -->
  <div class="card">
    <div class="tabs" role="tablist">
      <button class="tab active" id="tab-masuk" role="tab" aria-selected="true" aria-controls="panel-masuk" onclick="switchTab('masuk')">Masuk</button>
      <button class="tab" id="tab-daftar" role="tab" aria-selected="false" aria-controls="panel-daftar" onclick="switchTab('daftar')">Daftar</button>
    </div>

    <div class="panels">

      <!-- ═══ PANEL MASUK ═══ -->
      <div class="panel active" id="panel-masuk" role="tabpanel">
        <div class="panel-title">Selamat datang kembali</div>
        <div class="panel-sub">Masuk untuk melanjutkan perjalanan budayamu</div>

        <div class="alert alert-error" id="login-alert">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="login-alert-msg">Email atau password salah.</span>
        </div>

        <form action="../../BACKEND/login.php" method="POST" id="form-login" onsubmit="return doLogin(event)">
          <div class="field">
            <label class="field-label" for="login-email">Email</label>
            <div class="input-wrap">
              <input type="email" id="login-email" name="email" placeholder="nama@email.com" autocomplete="email">
              <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
              </span>
            </div>
            <div class="field-error" id="err-login-email">Masukkan email yang valid</div>
          </div>

          <div class="field">
            <label class="field-label" for="login-pw">Password</label>
            <div class="input-wrap">
              <input type="password" id="login-pw" name="password" placeholder="Masukkan password" autocomplete="current-password">
              <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
              <button class="pw-toggle" type="button" onclick="togglePw('login-pw', this)" aria-label="Tampilkan password">
                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div class="field-error" id="err-login-pw">Password tidak boleh kosong</div>
          </div>

          <div class="forgot-row">
            <a href="#" class="forgot-link">Lupa password?</a>
          </div>

          <button type="submit" class="btn-submit" id="btn-login">
            <span class="btn-text">Masuk</span>
            <span class="btn-spinner"><span class="spinner"></span></span>
          </button>
        </form>

        <div class="divider">
          <div class="divider-line"></div>
          <div class="divider-text">atau lanjutkan dengan</div>
          <div class="divider-line"></div>
        </div>

        <div class="social-row">
          <button class="btn-social" type="button">
            <svg width="18" height="18" viewBox="0 0 18 18"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.706A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
            Google
          </button>
          <button class="btn-social" type="button">
            <svg width="18" height="18" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/></svg>
            Facebook
          </button>
        </div>

        <div class="switch-text">
          Belum punya akun?
          <button type="button" class="switch-link" onclick="switchTab('daftar')">Daftar sekarang</button>
        </div>
      </div>

      <!-- ═══ PANEL DAFTAR ═══ -->
      <div class="panel" id="panel-daftar" role="tabpanel">

        <div class="steps-indicator" id="reg-steps">
          <div class="step-item active" id="step-1">
            <div class="step-dot" id="sdot-1">1</div>
            <div class="step-label">Akun</div>
          </div>
          <div class="step-item" id="step-2">
            <div class="step-dot" id="sdot-2">2</div>
            <div class="step-label">Profil</div>
          </div>
          <div class="step-item" id="step-3">
            <div class="step-dot" id="sdot-3">3</div>
            <div class="step-label">Selesai</div>
          </div>
        </div>

        <!-- Step 1: Akun -->
        <div id="reg-step-1">
          <div class="panel-title">Buat akunmu</div>
          <div class="panel-sub">Daftarkan email dan password untuk mulai</div>

          <div class="alert alert-error" id="reg-alert">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="reg-alert-msg">Terjadi kesalahan.</span>
          </div>

          <form id="form-reg-step1" onsubmit="return goStep2(event)">
            <div class="field">
              <label class="field-label" for="reg-email">Email</label>
              <div class="input-wrap">
                <input type="email" id="reg-email" name="email" placeholder="nama@email.com" autocomplete="email">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
                </span>
              </div>
              <div class="field-error" id="err-reg-email">Masukkan email yang valid</div>
            </div>

            <div class="field">
              <label class="field-label" for="reg-pw">Password</label>
              <div class="input-wrap">
                <input type="password" id="reg-pw" name="password" placeholder="Min. 8 karakter" autocomplete="new-password" oninput="checkStrength(this.value)">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>
                <button class="pw-toggle" type="button" onclick="togglePw('reg-pw', this)" aria-label="Tampilkan password">
                  <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              <div class="field-error" id="err-reg-pw">Password minimal 8 karakter</div>
              <div class="strength-wrap" id="strength-wrap">
                <div class="strength-bars">
                  <div class="s-bar" id="sb1"></div>
                  <div class="s-bar" id="sb2"></div>
                  <div class="s-bar" id="sb3"></div>
                  <div class="s-bar" id="sb4"></div>
                </div>
                <div class="strength-label" id="strength-label"></div>
              </div>
            </div>

            <div class="field">
              <label class="field-label" for="reg-pw2">Konfirmasi Password</label>
              <div class="input-wrap">
                <input type="password" id="reg-pw2" name="password_confirm" placeholder="Ulangi password" autocomplete="new-password">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </span>
                <button class="pw-toggle" type="button" onclick="togglePw('reg-pw2', this)" aria-label="Tampilkan password">
                  <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              <div class="field-error" id="err-reg-pw2">Password tidak cocok</div>
            </div>

            <button type="submit" class="btn-submit">
              <span class="btn-text">Lanjutkan</span>
              <span class="btn-spinner"><span class="spinner"></span></span>
            </button>
          </form>

          <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-text">atau daftar dengan</div>
            <div class="divider-line"></div>
          </div>

          <div class="social-row">
            <button class="btn-social" type="button">
              <svg width="18" height="18" viewBox="0 0 18 18"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.706A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
              Google
            </button>
            <button class="btn-social" type="button">
              <svg width="18" height="18" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/></svg>
              Facebook
            </button>
          </div>

          <div class="switch-text">
            Sudah punya akun?
            <button type="button" class="switch-link" onclick="switchTab('masuk')">Masuk di sini</button>
          </div>
        </div>

        <!-- Step 2: Profil -->
        <div id="reg-step-2" style="display:none">
          <div class="panel-title">Lengkapi profilmu</div>
          <div class="panel-sub">Ceritakan sedikit tentang dirimu</div>

          <div class="alert alert-error" id="reg-alert-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="reg-alert-msg-2">Terjadi kesalahan.</span>
          </div>

          <form id="form-reg-step2" action="../../BACKEND/register.php" method="POST" onsubmit="return doRegister(event)" enctype="multipart/form-data">
            <div class="avatar-upload">
              <div class="avatar-preview" id="avatar-preview-box" title="Pilih foto profil" onclick="document.getElementById('profile-pic-input').click()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
              <div class="avatar-upload-info">
                <button type="button" class="avatar-upload-btn" onclick="document.getElementById('profile-pic-input').click()">Unggah foto profil</button><br>
                JPG, PNG — maks 2MB
              </div>
              <input type="file" id="profile-pic-input" name="profile_pic" accept="image/*" style="display:none" onchange="previewAvatar(this)">
            </div>
            
            <input type="hidden" id="hidden-email" name="email">
            <input type="hidden" id="hidden-password" name="password">

            <div class="name-row">
              <div class="field">
                <label class="field-label" for="reg-fn">Nama Depan</label>
                <div class="input-wrap">
                  <input type="text" id="reg-fn" name="first_name" placeholder="Budi">
                  <span class="input-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  </span>
                </div>
                <div class="field-error" id="err-reg-fn">Nama depan wajib diisi</div>
              </div>
              <div class="field">
                <label class="field-label" for="reg-ln">Nama Belakang</label>
                <div class="input-wrap">
                  <input type="text" id="reg-ln" name="last_name" placeholder="Santoso">
                  <span class="input-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  </span>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="field-label" for="reg-phone">Nomor Telepon</label>
              <div class="input-wrap">
                <input type="tel" id="reg-phone" name="phone" placeholder="+62 812-xxxx-xxxx">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.87a16 16 0 0 0 6.29 6.29l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2z"/></svg>
                </span>
              </div>
            </div>

            <div class="field">
              <label class="field-label" for="reg-kota">Kota Asal</label>
              <div class="input-wrap">
                <input type="text" id="reg-kota" name="city" placeholder="Surabaya">
                <span class="input-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </span>
              </div>
            </div>

            <div class="agree-row">
              <input type="checkbox" class="agree-check" id="agree-tos" name="agree">
              <label for="agree-tos" class="agree-text">
                Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> Pawerti
              </label>
            </div>
            <div class="field-error" id="err-agree" style="margin-top:-14px;margin-bottom:14px;">Anda harus menyetujui syarat &amp; ketentuan</div>

            <button type="submit" class="btn-submit" id="btn-reg-submit">
              <span class="btn-text">Buat Akun</span>
              <span class="btn-spinner"><span class="spinner"></span></span>
            </button>
          </form>

          <div class="switch-text">
            <button type="button" class="switch-link" onclick="backStep1()" style="color:var(--muted);">Kembali</button>
          </div>
        </div>

        <!-- Step 3: Sukses -->
        <div id="reg-step-3" style="display:none; text-align:center; padding: 8px 0 12px;">
          <div class="success-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div class="panel-title" style="text-align:center; margin-bottom:8px;">Akun berhasil dibuat!</div>
          <div class="panel-sub" style="text-align:center;">
            Selamat datang di Pawerti.<br>Kami telah mengirim verifikasi ke emailmu.
          </div>
          <button type="button" class="btn-submit" style="margin-top:24px;" onclick="switchTab('masuk')">
            <span class="btn-text">Masuk Sekarang</span>
          </button>
        </div>

      </div><!-- end panel-daftar -->
    </div><!-- end panels -->
  </div><!-- end card -->

  <div class="page-footer">
    &copy; 2026 Pawerti &middot; Budaya Jawa Timur
  </div>
</div>

<script>
function switchTab(tab) {
  document.querySelectorAll('.tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.getElementById('tab-'+tab).classList.add('active');
  document.getElementById('tab-'+tab).setAttribute('aria-selected','true');
  document.getElementById('panel-'+tab).classList.add('active');
  clearErrors();
}

function clearErrors() {
  document.querySelectorAll('.field-error').forEach(e => e.classList.remove('show'));
  document.querySelectorAll('.alert').forEach(a => a.classList.remove('show'));
}

function togglePw(id, btn) {
  const inp = document.getElementById(id);
  const isHidden = inp.type === 'password';
  inp.type = isHidden ? 'text' : 'password';
  // swap icon: eye-off when visible
  btn.innerHTML = isHidden
    ? `<svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
    : `<svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
}

function checkStrength(val) {
  const wrap = document.getElementById('strength-wrap');
  const bars = ['sb1','sb2','sb3','sb4'].map(id => document.getElementById(id));
  const label = document.getElementById('strength-label');
  if (!val) { wrap.style.display = 'none'; return; }
  wrap.style.display = 'block';
  bars.forEach(b => b.className = 's-bar');
  let score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const cfg = [
    { cls:'fill-weak',   txt:'Lemah' },
    { cls:'fill-fair',   txt:'Cukup' },
    { cls:'fill-good',   txt:'Baik'  },
    { cls:'fill-strong', txt:'Kuat'  }
  ];
  const cur = cfg[Math.max(0, score-1)];
  for (let i = 0; i < score; i++) bars[i].classList.add(cur.cls);
  label.textContent = cur.txt;
}

function showErr(id, show) { document.getElementById(id).classList.toggle('show', show); }
function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

function previewAvatar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const previewBox = document.getElementById('avatar-preview-box');
      previewBox.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">`;
      previewBox.style.borderStyle = 'solid';
    }
    reader.readAsDataURL(input.files[0]);
  }
}

function doLogin(e) {
  e.preventDefault(); clearErrors();
  const email = document.getElementById('login-email').value.trim();
  const pw    = document.getElementById('login-pw').value;
  let ok = true;
  if (!isEmail(email)) { showErr('err-login-email', true); ok = false; }
  if (!pw)             { showErr('err-login-pw', true);    ok = false; }
  if (!ok) return false;
  const btn = document.getElementById('btn-login');
  btn.classList.add('loading');

  const formData = new FormData(e.target);

  console.log('Attempting login to: ../../BACKEND/login.php');
  fetch('../../BACKEND/login.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok: ' + response.statusText);
    }
    return response.json();
  })
  .then(data => {
    btn.classList.remove('loading');
    if (data.status === 'success') {
      window.location.href = 'index.php'; // Redirect on success
    } else {
      document.getElementById('login-alert-msg').textContent = data.message;
      document.getElementById('login-alert').classList.add('show');
    }
  })
  .catch(error => {
    btn.classList.remove('loading');
    document.getElementById('login-alert-msg').textContent = 'Terjadi kesalahan sistem atau koneksi.';
    document.getElementById('login-alert').classList.add('show');
    console.error('Login Error:', error);
  });
  return false;
}

function goStep2(e) {
  e.preventDefault(); clearErrors();
  const email = document.getElementById('reg-email').value.trim();
  const pw    = document.getElementById('reg-pw').value;
  const pw2   = document.getElementById('reg-pw2').value;
  let ok = true;
  if (!isEmail(email)) { showErr('err-reg-email', true); ok = false; }
  if (pw.length < 8)   { showErr('err-reg-pw', true);   ok = false; }
  if (pw !== pw2)      { showErr('err-reg-pw2', true);  ok = false; }
  if (!ok) return false;
  document.getElementById('hidden-email').value    = email;
  document.getElementById('hidden-password').value = pw;
  document.getElementById('reg-step-1').style.display = 'none';
  document.getElementById('reg-step-2').style.display = 'block';
  setStep(2);
  return false;
}

function backStep1() {
  document.getElementById('reg-step-2').style.display = 'none';
  document.getElementById('reg-step-1').style.display = 'block';
  setStep(1);
}

function doRegister(e) {
  e.preventDefault(); clearErrors();
  const fn    = document.getElementById('reg-fn').value.trim();
  const agree = document.getElementById('agree-tos').checked;
  let ok = true;
  if (!fn)    { showErr('err-reg-fn', true);  ok = false; }
  if (!agree) { showErr('err-agree', true);   ok = false; }
  if (!ok) return false;
  const btn = document.getElementById('btn-reg-submit');
  btn.classList.add('loading');
  fetch('../../BACKEND/register.php', {
    method: 'POST',
    body: new FormData(e.target)
  })
  .then(res => res.json())
  .then(data => {
    btn.classList.remove('loading');
    if (data.status === 'success') {
      document.getElementById('reg-step-2').style.display = 'none';
      document.getElementById('reg-step-3').style.display = 'block';
      setStep(3);
    } else {
      document.getElementById('reg-alert-msg-2').textContent = data.message;
      document.getElementById('reg-alert-2').classList.add('show');
      if (data.message.toLowerCase().includes('email')) {
        backStep1();
        document.getElementById('reg-alert-msg').textContent = data.message;
        document.getElementById('reg-alert').classList.add('show');
      }
    }
  })
  .catch(err => {
    btn.classList.remove('loading');
    document.getElementById('reg-alert-msg-2').textContent = 'Terjadi kesalahan sistem.';
    document.getElementById('reg-alert-2').classList.add('show');
  });
  return false;
}

function setStep(n) {
  for (let i = 1; i <= 3; i++) {
    const item = document.getElementById('step-'+i);
    item.classList.remove('active','done');
    if (i < n)        item.classList.add('done');
    else if (i === n) item.classList.add('active');
  }
}
</script>
</body>
</html>