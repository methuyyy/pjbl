<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Pawerti</title>
    <link rel="icon" href="data:,">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #8B2500;
            --primary-light: #C4401A;
            --primary-pale: #FFF5F2;
            --accent: #D4A017;
            --accent-light: #F7D97A;
            --sidebar-bg: #1C0D00;
            --sidebar-muted: #9B7B5A;
            --sidebar-text: #F5DEB3;
            --bg: #F9F5F0;
            --text: #2C1500;
            --text-muted: #7A5C3A;
            --border: #E8DDD0;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--sidebar-bg);
        }

        /* ── LEFT PANEL ── */
        .login-left {
            flex: 1;
            background: linear-gradient(160deg, #1C0D00 0%, #3B1200 60%, #6B2800 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            border: 60px solid rgba(212, 160, 23, 0.06);
            top: -80px;
            right: -80px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            border: 40px solid rgba(139, 37, 0, 0.15);
            bottom: -60px;
            left: -60px;
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(212, 160, 23, 0.2);
            position: relative;
            z-index: 1;
        }

        .brand-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .brand-logo-fallback {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo-fallback svg {
            width: 32px;
            height: 32px;
            color: #fff;
        }

        .login-brand-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent-light);
            text-align: center;
            line-height: 1.3;
            position: relative;
            z-index: 1;
        }

        .login-brand-sub {
            font-size: 11px;
            color: var(--sidebar-muted);
            text-align: center;
            margin-top: 6px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
            font-weight: 400;
        }

        .login-features {
            margin-top: 52px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            width: 100%;
            max-width: 300px;
            position: relative;
            z-index: 1;
        }

        .login-feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: var(--sidebar-text);
            font-size: 13.5px;
            font-weight: 400;
        }

        .login-feature-icon {
            width: 38px;
            height: 38px;
            background: rgba(212, 160, 23, 0.12);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-light);
            flex-shrink: 0;
        }

        .login-feature-icon svg {
            width: 17px;
            height: 17px;
        }

        /* ── RIGHT PANEL ── */
        .login-right {
            width: 440px;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
        }

        .login-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 4px 20px rgba(139, 37, 0, 0.22);
            font-family: 'Poppins', sans-serif;
        }

        .login-name-hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 28px;
        }

        .login-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
            text-align: center;
        }

        .login-sub {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 32px;
            text-align: center;
            font-weight: 400;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: var(--text);
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .input-icon svg {
            width: 15px;
            height: 15px;
        }

        .form-control {
            width: 100%;
            padding: 11px 13px 11px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 13.5px;
            background: var(--bg);
            color: var(--text);
            outline: none;
            transition: border .2s, box-shadow .2s;
            -webkit-appearance: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(139, 37, 0, 0.10);
        }

        .pw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            padding: 4px;
            transition: color .2s;
        }

        .pw-toggle:hover {
            color: var(--primary);
        }

        .pw-toggle svg {
            width: 15px;
            height: 15px;
        }

        .login-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: var(--text-muted);
            cursor: pointer;
        }

        .remember-row input {
            accent-color: var(--primary);
        }

        .forgot-link {
            font-size: 12.5px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .login-error {
            background: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF9A9A;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12.5px;
            margin-bottom: 14px;
            display: none;
            align-items: center;
            gap: 8px;
        }

        .login-error.show {
            display: flex;
        }

        .login-error svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .15s;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn svg {
            width: 16px;
            height: 16px;
        }

        .login-btn:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-hint {
            font-size: 11.5px;
            color: var(--text-muted);
            text-align: center;
            margin-top: 18px;
        }

        .login-hint strong {
            color: var(--primary);
            font-weight: 600;
        }

        @media(max-width: 700px) {
            .login-left {
                display: none;
            }

            .login-right {
                width: 100%;
                padding: 40px 28px;
            }
        }
    </style>
</head>

<body>

    <!-- LEFT -->
    <div class="login-left">
        <div class="brand-logo">
            <div class="brand-logo-fallback">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </div>
        </div>
        <div class="login-brand-title">Pawerti<br>Admin Panel</div>
        <div class="login-brand-sub">Jelajahi Kekayaan Budaya Jawa</div>

        <div class="login-features">
            <div class="login-feature-item">
                <div class="login-feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                Kelola Event dan Kegiatan Budaya
            </div>
            <div class="login-feature-item">
                <div class="login-feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                </div>
                Manajemen Konten dan Artikel
            </div>
            <div class="login-feature-item">
                <div class="login-feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                </div>
                Laporan dan Statistik Real-time
            </div>
            <div class="login-feature-item">
                <div class="login-feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                Manajemen Pengguna dan Peran
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="login-right">
        <div class="login-avatar">AS</div>
        <div class="login-name-hint">Admin Sanjaya</div>
        <div class="login-title">Masuk ke Admin Panel</div>
        <div class="login-sub">Silakan masukkan kredensial akun Anda</div>

        <div class="login-form">
            <div class="login-error" id="login-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                Email atau password salah. Coba lagi.
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <div class="input-wrap">
                    <input type="text" class="form-control" id="login-username" placeholder="admin" value="admin">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <input type="password" class="form-control" id="login-password" placeholder="Masukkan password" value="admin123">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </span>
                    <button class="pw-toggle" type="button" id="pw-toggle-btn" onclick="togglePw()">
                        <svg id="pw-eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="login-meta">
                <label class="remember-row">
                    <input type="checkbox" checked> Ingat saya
                </label>
                <a href="#" class="forgot-link">Lupa password?</a>
            </div>

            <button class="login-btn" onclick="doLogin()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                    <polyline points="10 17 15 12 10 7" />
                    <line x1="15" y1="12" x2="3" y2="12" />
                </svg>
                Masuk
            </button>

            <div class="login-hint">
                Demo: <strong>admin</strong> / <strong>admin123</strong>
            </div>
        </div>
    </div>

    <script>
        function doLogin() {
            const username = document.getElementById('login-username').value.trim();
            const pw = document.getElementById('login-password').value;
            const err = document.getElementById('login-error');
            const btn = document.querySelector('.login-btn');

            if (!username || !pw) {
                err.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Username dan password wajib diisi.`;
                err.classList.add('show');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            // Menggunakan URLSearchParams agar data terkirim sebagai application/x-www-form-urlencoded
            // yang lebih mudah dibaca oleh $_POST di PHP
            const params = new URLSearchParams();
            params.append('username', username);
            params.append('password', pw);

            fetch('../../../BACKEND/login_admin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params
                })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg> Masuk';

                    if (data.status === 'success') {
                        err.classList.remove('show' );
                        window.location.href = 'dashboard.php';
                    } else {
                        err.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> ${data.message}`;
                        err.classList.add('show');
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg> Masuk';
                    err.textContent = 'Terjadi kesalahan sistem atau koneksi.';
                    err.classList.add('show');
                    console.error('Login Error:', error);
                });
        }

        function togglePw() {
            const inp = document.getElementById('login-password');
            const icon = document.getElementById('pw-eye-icon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                inp.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }

        document.getElementById('login-password').addEventListener('keydown', e => {
            if (e.key === 'Enter') doLogin();
        });
    </script>
</body>

</html>