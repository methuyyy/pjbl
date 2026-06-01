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
    <title>Kelola Kategori - Admin</title>
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

        .icon-item:hover {
            border-color: var(--primary) !important;
            background: var(--primary-pale) !important;
            transform: translateY(-2px);
        }

        .icon-item:hover i {
            color: var(--primary) !important;
        }

        /* Perbaiki kolom icon di tabel agar garisnya lurus */
        #category-table td {
            display: table-cell !important;
            vertical-align: middle !important;
        }

        /* Autocomplete styles */
        .autocomplete-container {
            position: relative;
            flex: 1;
        }

        .icon-input-wrapper {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .icon-box {
            width: 46px;
            height: 46px;
            background: #f9f5f0;
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .icon-box:hover {
            border-color: var(--primary);
            background: var(--primary-pale);
        }

        .icon-box:hover i {
            color: var(--primary);
        }

        .icon-name-box {
            flex: 1;
            padding: 10px 16px;
            background: #f9f5f0;
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
        }

        .icon-name-box input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            color: #2C1500;
        }

        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 8px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .autocomplete-dropdown.show {
            display: block;
        }

        .autocomplete-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .autocomplete-item:hover {
            background: #f9f5f0;
        }

        .autocomplete-item:first-child {
            border-radius: 12px 12px 0 0;
        }

        .autocomplete-item:last-child {
            border-radius: 0 0 12px 12px;
        }

        .autocomplete-icon-box {
            width: 36px;
            height: 36px;
            background: #f9f5f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .autocomplete-item:hover .autocomplete-icon-box {
            background: #fff;
        }
    </style>
</head>

<body>
    <?php include '../components/sidebar.php'; ?>

    <main class="main">
        <?php include '../components/topbar.php'; ?>

        <div class="content" id="page-kategori">
            <div class="page-header">
                <div>
                    <div class="page-title">Kelola Kategori</div>
                    <div class="page-sub">Kelola kategori event budaya</div>
                </div>
                <button class="btn btn-primary" onclick="openAddCategoryModal()"><i class="fas fa-plus"></i> Tambah Kategori</button>
            </div>
            <div class="card">
                <div class="table-wrap">
                    <table id="category-table">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Icon</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="category-list-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Kategori -->
        <div id="category-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
            <div class="modal-box" style="max-width:500px; text-align:left;">
                <div class="modal-title" id="cat-modal-title">Tambah Kategori</div>
                <form id="category-form">
                    <input type="hidden" name="id" id="cat-id">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="cat-nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Icon</label>
                        <div class="icon-input-wrapper">
                            <div class="icon-box" onclick="openIconPicker()">
                                <i id="selected-icon-display" class="fas fa-ticket-simple" style="font-size: 1.5rem; color: #8B2500;"></i>
                            </div>
                            <div class="autocomplete-container">
                                <div class="icon-name-box">
                                    <input type="text" id="cat-icon-input" placeholder="Ketik nama icon..." value="fa-ticket-simple">
                                </div>
                                <div id="autocomplete-dropdown" class="autocomplete-dropdown"></div>
                            </div>
                        </div>
                        <input type="hidden" name="icon" id="cat-icon" value="fa-ticket-simple">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="cat-desc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="modal-actions" style="display:flex; gap:12px; margin-top:24px;">
                        <button type="button" class="btn btn-outline" onclick="closeCategoryModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Icon Picker Modal -->
        <div id="icon-picker-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:1500; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
            <div style="background:#fff; border-radius:20px; max-width:850px; width:95%; max-height:85vh; overflow:hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                <div style="padding:20px 28px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border); background:#fdfbfa;">
                    <div style="font-size:19px; font-weight:700; color:#2C1500;">
                        <i class="fas fa-icons" style="color:#8B2500; margin-right:8px;"></i> Pilih Icon
                    </div>
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeIconPicker()">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
                <div style="padding:16px 28px; border-bottom:1px solid var(--border); background:#fff;">
                    <div class="form-group" style="margin-bottom:0;">
                        <div style="display:flex; gap:10px; align-items:center; padding:10px 16px; background:#f9f5f0; border-radius:12px;">
                            <i class="fas fa-search" style="color:#7A5C3A;"></i>
                            <input type="text" id="icon-search" class="form-control" style="border:none; padding:0; outline:none; box-shadow:none; background:transparent; font-size:0.95rem;" placeholder="Cari icon...">
                        </div>
                    </div>
                </div>
                <div id="icon-grid" style="padding:20px 28px; overflow:auto; max-height:55vh; display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:14px;">
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div id="confirm-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:2500; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
            <div class="modal-box" style="background:#fff; padding:35px; border-radius:24px; max-width:420px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div id="confirm-icon-wrap" style="width:70px; height:70px; background:#fff3e0; color:#f57c00; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 id="confirm-title" style="margin-bottom:10px; font-size:20px; font-weight:700;">Konfirmasi Tindakan</h3>
                <p id="confirm-message" style="color:#666; margin-bottom:30px; font-size:14px; line-height:1.6;">Apakah Anda yakin ingin menghapus kategori ini?</p>
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
        // Daftar icon Font Awesome 6.5 Free yang benar dan lengkap
        const fontAwesomeIcons = [
            'fa-star', 'fa-heart', 'fa-home', 'fa-user', 'fa-bell', 'fa-camera', 'fa-music',
            'fa-film', 'fa-gamepad', 'fa-shopping-bag', 'fa-book', 'fa-pen', 'fa-palette',
            'fa-masks-theater', 'fa-leaf', 'fa-fire', 'fa-sun', 'fa-moon', 'fa-cloud',
            'fa-umbrella', 'fa-globe', 'fa-plane', 'fa-car', 'fa-train', 'fa-bus',
            'fa-ship', 'fa-hotel', 'fa-utensils', 'fa-cookie', 'fa-cocktail', 'fa-gift',
            'fa-tag', 'fa-shopping-cart', 'fa-credit-card', 'fa-wallet', 'fa-money-bill',
            'fa-building', 'fa-city', 'fa-landmark', 'fa-school', 'fa-hospital',
            'fa-flask', 'fa-microscope', 'fa-atom', 'fa-rocket', 'fa-laptop',
            'fa-mobile-screen-button', 'fa-tv', 'fa-headphones', 'fa-gamepad-modern', 'fa-dice',
            'fa-puzzle-piece', 'fa-cube', 'fa-anchor', 'fa-life-ring', 'fa-compass',
            'fa-location-dot', 'fa-map', 'fa-location-crosshairs', 'fa-camera-retro',
            'fa-image', 'fa-video', 'fa-microphone', 'fa-podcast', 'fa-ticket',
            'fa-ticket-alt', 'fa-ticket-simple', 'fa-calendar', 'fa-calendar-days', 'fa-clock',
            'fa-hourglass', 'fa-stopwatch', 'fa-trophy', 'fa-medal', 'fa-award', 'fa-ribbon',
            'fa-flag', 'fa-flag-checkered', 'fa-futbol', 'fa-basketball', 'fa-football',
            'fa-baseball', 'fa-tennis-ball', 'fa-hockey-puck', 'fa-volleyball', 'fa-swimmer',
            'fa-dumbbell', 'fa-hands-helping', 'fa-hand-holding-heart', 'fa-heartbeat',
            'fa-heart-pulse', 'fa-heart-circle-check', 'fa-heart-circle-plus',
            'fa-heart-circle-xmark', 'fa-heart-crack', 'fa-spa', 'fa-face-smile',
            'fa-face-laugh', 'fa-face-tongue', 'fa-face-grin', 'fa-face-surprise',
            'fa-face-sad-tear', 'fa-face-angry', 'fa-user-tie', 'fa-user-nurse',
            'fa-user-doctor', 'fa-user-injured', 'fa-user-graduate', 'fa-user-astronaut',
            'fa-user-secret', 'fa-user-ninja', 'fa-hat-wizard', 'fa-hat-cowboy',
            'fa-mitten', 'fa-glove', 'fa-shoe-prints', 'fa-socks', 'fa-shirt',
            'fa-dress', 'fa-hood-cloak', 'fa-crown', 'fa-gem', 'fa-diamond', 'fa-ring',
            'fa-guitar', 'fa-drum', 'fa-piano', 'fa-violin', 'fa-trumpet', 'fa-horn',
            'fa-megaphone', 'fa-radio', 'fa-wifi', 'fa-tower-broadcast', 'fa-satellite',
            'fa-satellite-dish', 'fa-wand-magic', 'fa-wand-sparkles', 'fa-ghost',
            'fa-spider', 'fa-spider-web', 'fa-cat', 'fa-dog', 'fa-fish', 'fa-dragon',
            'fa-horse', 'fa-cow', 'fa-pig', 'fa-sheep', 'fa-chicken', 'fa-fox',
            'fa-wolf', 'fa-bear', 'fa-panda', 'fa-koala', 'fa-kangaroo', 'fa-elephant',
            'fa-giraffe', 'fa-zebra', 'fa-camel', 'fa-rhino', 'fa-hippo', 'fa-lion',
            'fa-tiger', 'fa-leopard', 'fa-bird', 'fa-owl', 'fa-dove', 'fa-eagle',
            'fa-falcon', 'fa-hawk', 'fa-parrot', 'fa-butterfly', 'fa-bug', 'fa-ant',
            'fa-bee', 'fa-beetle', 'fa-ladybug', 'fa-worm', 'fa-snake', 'fa-frog',
            'fa-turtle', 'fa-lizard', 'fa-crocodile', 'fa-whale', 'fa-dolphin', 'fa-shark',
            'fa-crab', 'fa-lobster', 'fa-octopus', 'fa-squid', 'fa-jellyfish', 'fa-snail',
            'fa-wheat', 'fa-seedling', 'fa-sprout', 'fa-tree', 'fa-palm-tree', 'fa-apple-whole',
            'fa-lemon', 'fa-orange', 'fa-banana', 'fa-grapes', 'fa-strawberry', 'fa-raspberry',
            'fa-blueberry', 'fa-kiwi', 'fa-mango', 'fa-pineapple', 'fa-watermelon', 'fa-peach',
            'fa-pear', 'fa-cherry', 'fa-carrot', 'fa-pepper-hot', 'fa-pepper', 'fa-cabbage',
            'fa-leek', 'fa-garlic', 'fa-onion', 'fa-potato', 'fa-tomato', 'fa-cucumber',
            'fa-eggplant', 'fa-pumpkin', 'fa-corn', 'fa-bread-slice', 'fa-croissant',
            'fa-donut', 'fa-cake', 'fa-cookie-bite', 'fa-candy-cane', 'fa-lollipop',
            'fa-cupcake', 'fa-pizza-slice', 'fa-burger', 'fa-hotdog', 'fa-french-fries',
            'fa-hamburger', 'fa-cheese', 'fa-bottle-water', 'fa-wine-glass', 'fa-beer-mug-empty',
            'fa-martini-glass', 'fa-mug-hot', 'fa-tea', 'fa-soda-can', 'fa-soda-bottle',
            'fa-jar', 'fa-box', 'fa-box-open', 'fa-package', 'fa-truck', 'fa-plane-departure',
            'fa-plane-arrival', 'fa-taxi', 'fa-subway', 'fa-train-tram', 'fa-motorcycle',
            'fa-bicycle', 'fa-truck-pickup', 'fa-car-side', 'fa-car-front', 'fa-car-taxi',
            'fa-bolt', 'fa-flame', 'fa-fire-flame-curved', 'fa-snowflake', 'fa-snowman',
            'fa-cloud-rain', 'fa-cloud-sun', 'fa-cloud-moon', 'fa-cloud-showers-heavy',
            'fa-cloud-bolt', 'fa-thunderstorm', 'fa-cloud-lightning', 'fa-rainbow',
            'fa-umbrella-beach', 'fa-beach-umbrella', 'fa-hat-sun', 'fa-sunglasses',
            'fa-graduation-cap', 'fa-diploma', 'fa-scroll', 'fa-book-open', 'fa-book-reader',
            'fa-book-heart', 'fa-book-medical', 'fa-newspaper', 'fa-magazine', 'fa-pencil',
            'fa-ruler', 'fa-ruler-combined', 'fa-eraser', 'fa-paperclip', 'fa-sticky-note',
            'fa-paper-plane', 'fa-jet-fighter', 'fa-marker', 'fa-paint-roller', 'fa-brush',
            'fa-paint-brush', 'fa-spray-can', 'fa-folder', 'fa-folder-open', 'fa-folder-plus',
            'fa-folder-minus', 'fa-file', 'fa-file-alt', 'fa-file-lines', 'fa-file-pdf',
            'fa-file-word', 'fa-file-excel', 'fa-file-powerpoint', 'fa-file-image',
            'fa-file-video', 'fa-file-audio', 'fa-file-code', 'fa-file-zipper', 'fa-folder-tree',
            'fa-link', 'fa-link-slash', 'fa-unlink', 'fa-share', 'fa-share-nodes',
            'fa-reply', 'fa-reply-all', 'fa-forward', 'fa-retweet', 'fa-sync',
            'fa-refresh', 'fa-rotate', 'fa-spinner', 'fa-cog', 'fa-sliders',
            'fa-wrench', 'fa-screwdriver', 'fa-screwdriver-wrench', 'fa-hammer', 'fa-saw',
            'fa-axe', 'fa-shovel', 'fa-pickaxe', 'fa-trowel', 'fa-wheelbarrow',
            'fa-bucket', 'fa-soap', 'fa-sponge', 'fa-hand-sparkles', 'fa-hand-dots',
            'fa-hand-fist', 'fa-hand-back-fist', 'fa-hand-peace', 'fa-hand-holding',
            'fa-hands', 'fa-hands-clapping', 'fa-hands-praying', 'fa-handshake',
            'fa-sparkles', 'fa-lightbulb', 'fa-lightbulb-on', 'fa-lamp', 'fa-table-lamp',
            'fa-floor-lamp', 'fa-lantern', 'fa-candle', 'fa-solar-panel', 'fa-sun-haze',
            'fa-cloud-fog', 'fa-wind', 'fa-wind-turbine', 'fa-ice-cube', 'fa-ice-cream',
            'fa-beach-ball', 'fa-sandwich', 'fa-salad', 'fa-avocado', 'fa-kiwi-fruit',
            'fa-cherries', 'fa-bottle-wine', 'fa-parcel', 'fa-truck-fast', 'fa-truck-moving',
            'fa-walking', 'fa-running', 'fa-hiking', 'fa-climbing', 'fa-swimming-pool',
            'fa-diving', 'fa-sailing', 'fa-surfing', 'fa-kayaking', 'fa-canoeing',
            'fa-rowing', 'fa-skiing', 'fa-snowboarding', 'fa-sledding', 'fa-ice-skating',
            'fa-roller-skating', 'fa-skateboarding', 'fa-biking', 'fa-hot-air-balloon',
            'fa-parachute-box', 'fa-parachute', 'fa-kite', 'fa-drone', 'fa-ufo',
            'fa-telescope', 'fa-binoculars', 'fa-microscope', 'fa-flask-vial', 'fa-test-tube',
            'fa-dna', 'fa-planet-ringed', 'fa-earth-americas', 'fa-earth-europe', 'fa-earth-asia',
            'fa-earth-africa', 'fa-earth-oceania', 'fa-globe-americas', 'fa-globe-europe',
            'fa-globe-asia', 'fa-globe-africa', 'fa-map-alt', 'fa-map-pin', 'fa-compass-alt',
            'fa-cube-alt', 'fa-cubes', 'fa-boxes-stacked', 'fa-list', 'fa-list-ul',
            'fa-list-ol', 'fa-list-check', 'fa-clipboard', 'fa-clipboard-list',
            'fa-clipboard-check', 'fa-clipboard-user', 'fa-clipboard-heart', 'fa-clipboard-notes',
            'fa-envelope', 'fa-envelope-open', 'fa-envelope-open-text', 'fa-envelope-heart',
            'fa-phone', 'fa-mobile-alt', 'fa-tablet', 'fa-desktop', 'fa-desktop-alt',
            'fa-headphones-alt', 'fa-headphones-simple', 'fa-headset', 'fa-speaker',
            'fa-volume-high', 'fa-volume-low', 'fa-volume-off', 'fa-volume-xmark', 'fa-volume-down',
            'fa-volume-up', 'fa-volume-mute', 'fa-music-alt', 'fa-microphone-alt',
            'fa-microphone-slash', 'fa-microphone-lines', 'fa-microphone-lines-slash',
            'fa-podcast-alt', 'fa-rss', 'fa-bluetooth', 'fa-usb', 'fa-hard-drive',
            'fa-memory', 'fa-microchip', 'fa-sd-card', 'fa-floppy-disk', 'fa-compact-disc',
            'fa-dvd', 'fa-gamepad-nintendo', 'fa-gamepad-playstation', 'fa-gamepad-xbox',
            'fa-chart-line', 'fa-chart-area', 'fa-chart-bar', 'fa-chart-column', 'fa-chart-pie',
            'fa-table', 'fa-table-cells', 'fa-table-list', 'fa-table-columns', 'fa-color-palette',
            'fa-highlighter', 'fa-marker-tip', 'fa-pen-alt', 'fa-pen-clip', 'fa-pen-nib',
            'fa-quill', 'fa-feather', 'fa-feather-alt', 'fa-seed', 'fa-seedling-alt',
            'fa-plant', 'fa-plant-wilt', 'fa-plant-pot', 'fa-plant-cactus', 'fa-plant-flower',
            'fa-tree-alt', 'fa-tree-pine', 'fa-leaf-alt', 'fa-leaves', 'fa-leaf-heart',
            'fa-leaf-clover', 'fa-clover', 'fa-lotus', 'fa-rose', 'fa-tulip', 'fa-sunflower',
            'fa-daisy', 'fa-flower', 'fa-flower-alt', 'fa-mushroom', 'fa-mushroom-alt',
            'fa-bacteria', 'fa-virus', 'fa-virus-covid', 'fa-droplet', 'fa-droplet-slash',
            'fa-droplet-percent', 'fa-heart-broken', 'fa-hands-heart', 'fa-gift-alt',
            'fa-gift-box', 'fa-package-gift', 'fa-gift-card', 'fa-gift-tag', 'fa-tags',
            'fa-calendar-check', 'fa-calendar-day', 'fa-calendar-week', 'fa-calendar-month',
            'fa-calendar-plus', 'fa-calendar-minus', 'fa-calendar-xmark', 'fa-calendar-arrow-down',
            'fa-calendar-arrow-up', 'fa-calendar-heart', 'fa-clock-alt', 'fa-clock-rotate-left',
            'fa-clock-rotate-right', 'fa-stopwatch-20', 'fa-hourglass-half', 'fa-hourglass-start',
            'fa-hourglass-end', 'fa-trophy-alt', 'fa-trophy-star', 'fa-trophy-heart',
            'fa-medal-alt', 'fa-medal-star', 'fa-award-alt', 'fa-award-star', 'fa-award-heart',
            'fa-ribbon-alt', 'fa-ribbon-star', 'fa-flag-alt', 'fa-dumbbell-alt', 'fa-weight-hanging',
            'fa-weight-scale', 'fa-weight', 'fa-balance-scale', 'fa-balance-scale-right', 'fa-balance-scale-left',
            'fa-people-group', 'fa-users', 'fa-user-friends', 'fa-user-group', 'fa-user-plus',
            'fa-user-minus', 'fa-user-check', 'fa-user-xmark', 'fa-user-lock', 'fa-user-unlock',
            'fa-user-clock', 'fa-user-slash', 'fa-user-shield', 'fa-user-tag', 'fa-user-gear',
            'fa-user-edit', 'fa-user-pen', 'fa-user-id-card', 'fa-id-card', 'fa-passport',
            'fa-bookmark', 'fa-bookmark-slash', 'fa-bookmark-check', 'fa-bookmark-xmark', 'fa-bookmark-star',
            'fa-bookmark-heart', 'fa-heart-bookmark', 'fa-star-bookmark', 'fa-book-bookmark'
        ];

        let currentIcon = 'fa-ticket-simple';

        // Highlight current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'kategori.php') {
                    link.classList.add('active');
                }
            });
            loadCategories();
            renderIconPicker('');

            const iconInput = document.getElementById('cat-icon-input');
            const dropdown = document.getElementById('autocomplete-dropdown');

            // Event listener untuk input icon
            iconInput.addEventListener('input', function(e) {
                const value = e.target.value.toLowerCase();
                currentIcon = value;
                document.getElementById('cat-icon').value = value;

                // Update preview icon
                updateIconPreview(value);

                // Show autocomplete
                showAutocomplete(value);
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.autocomplete-container')) {
                    dropdown.classList.remove('show');
                }
            });

            document.getElementById('icon-search').addEventListener('input', function(e) {
                renderIconPicker(e.target.value.toLowerCase());
            });
        });

        function updateIconPreview(iconClass) {
            const iconDisplay = document.getElementById('selected-icon-display');
            if (iconClass) {
                let fullClass = iconClass;
                if (!iconClass.startsWith('fas ')) {
                    fullClass = 'fas ' + iconClass;
                }
                iconDisplay.className = fullClass;
            } else {
                iconDisplay.className = 'fas fa-ticket-simple';
            }
        }

        function showAutocomplete(query) {
            const dropdown = document.getElementById('autocomplete-dropdown');

            if (query.length < 1) {
                dropdown.classList.remove('show');
                return;
            }

            const matches = fontAwesomeIcons.filter(icon =>
                icon.toLowerCase().includes(query.toLowerCase())
            ).slice(0, 8);

            if (matches.length === 0) {
                dropdown.classList.remove('show');
                return;
            }

            dropdown.innerHTML = matches.map(icon => `
                <div class="autocomplete-item" onclick="selectAutocompleteIcon('${icon}')">
                    <div class="autocomplete-icon-box">
                        <i class="fas ${icon}" style="font-size:1.1rem; color:#8B2500;"></i>
                    </div>
                    <span style="font-size:0.9rem; color:#7A5C3A; font-weight:500;">${icon}</span>
                </div>
            `).join('');

            dropdown.classList.add('show');
        }

        function selectAutocompleteIcon(iconClass) {
            currentIcon = iconClass;
            document.getElementById('cat-icon-input').value = iconClass;
            document.getElementById('cat-icon').value = iconClass;
            updateIconPreview(iconClass);
            document.getElementById('autocomplete-dropdown').classList.remove('show');
        }

        function renderIconPicker(filterText) {
            const iconGrid = document.getElementById('icon-grid');
            const filteredIcons = fontAwesomeIcons.filter(icon => icon.includes(filterText));

            iconGrid.innerHTML = filteredIcons.map(icon => `
                <div class="icon-item" style="display:flex; flex-direction:row; align-items:center; gap:12px; padding:14px 18px; border:1px solid var(--border); border-radius:12px; cursor:pointer; transition:all 0.2s; background:#fff;" onclick="selectIcon('${icon}')">
                    <div style="width:40px; height:40px; background:#f9f5f0; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas ${icon}" style="font-size:1.3rem; color:#8B2500;"></i>
                    </div>
                    <span style="font-size:0.9rem; color:#7A5C3A; font-weight:500;">${icon}</span>
                </div>
            `).join('');
        }

        function openIconPicker() {
            document.getElementById('icon-picker-modal').style.display = 'flex';
            document.getElementById('icon-search').value = '';
            renderIconPicker('');
        }

        function closeIconPicker() {
            document.getElementById('icon-picker-modal').style.display = 'none';
        }

        function selectIcon(iconClass) {
            currentIcon = iconClass;
            document.getElementById('cat-icon').value = iconClass;
            document.getElementById('cat-icon-input').value = iconClass;
            document.getElementById('selected-icon-display').className = `fas ${iconClass}`;
            closeIconPicker();
        }

        async function loadCategories() {
            try {
                const res = await fetch('../../../BACKEND/admin_categories.php?action=list');
                const data = await res.json();
                if (data.status === 'success') {
                    renderCategories(data.data);
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderCategories(categories) {
            const tbody = document.getElementById('category-list-body');
            tbody.innerHTML = categories.map(cat => {
                let iconClass = cat.icon || 'fa-ticket-simple';
                if (!iconClass.includes('fa-')) {
                    iconClass = 'fa-' + iconClass;
                }
                if (!iconClass.startsWith('fa-') && !iconClass.startsWith('fas ')) {
                    iconClass = 'fas ' + iconClass;
                }
                if (!iconClass.includes(' ')) {
                    iconClass = 'fas ' + iconClass;
                }
                const iconName = iconClass.replace('fas ', '').replace('fa-', '');

                return `
          <tr>
            <td><strong>${cat.nama_kategori}</strong></td>
            <td>
                <div style="display:flex; align-items:center; gap:10px;">
                    <i class="${iconClass}" style="font-size: 1.25rem; min-width: 28px; text-align: center;"></i>
                    <span style="font-size:0.85rem; color:var(--text-muted);">${iconName}</span>
                </div>
            </td>
            <td>${cat.deskripsi || '-'}</td>
            <td class="col-aksi">
              <button class="btn btn-outline btn-sm" onclick="openEditCategoryModal(${cat.id})">Edit</button>
              <button class="btn btn-danger btn-sm" onclick="confirmDeleteCategory(${cat.id})">Hapus</button>
            </td>
          </tr>
                `;
            }).join('');
        }

        async function openEditCategoryModal(id) {
            try {
                const res = await fetch(`../../../BACKEND/admin_categories.php?action=get&id=${id}`);
                const data = await res.json();
                if (data.status === 'success') {
                    const cat = data.data;
                    let iconClass = cat.icon || 'fa-ticket-simple';
                    if (!iconClass.includes('fa-')) {
                        iconClass = 'fa-' + iconClass;
                    }
                    if (!iconClass.startsWith('fa-') && !iconClass.startsWith('fas ')) {
                        iconClass = 'fas ' + iconClass;
                    }
                    if (!iconClass.includes(' ')) {
                        iconClass = 'fas ' + iconClass;
                    }

                    document.getElementById('cat-id').value = cat.id;
                    document.getElementById('cat-nama').value = cat.nama_kategori;
                    document.getElementById('cat-icon').value = iconClass;
                    document.getElementById('cat-icon-input').value = iconClass;
                    document.getElementById('selected-icon-display').className = iconClass;
                    document.getElementById('cat-desc').value = cat.deskripsi;
                    document.getElementById('cat-modal-title').textContent = 'Edit Kategori';
                    document.getElementById('category-modal').style.display = 'flex';
                }
            } catch (err) {
                console.error(err);
            }
        }

        function openAddCategoryModal() {
            document.getElementById('cat-id').value = '';
            document.getElementById('cat-nama').value = '';
            document.getElementById('cat-icon').value = 'fa-ticket-simple';
            document.getElementById('cat-icon-input').value = 'fa-ticket-simple';
            document.getElementById('selected-icon-display').className = 'fas fa-ticket-simple';
            document.getElementById('cat-desc').value = '';
            document.getElementById('cat-modal-title').textContent = 'Tambah Kategori';
            document.getElementById('category-modal').style.display = 'flex';
        }

        function closeCategoryModal() {
            document.getElementById('category-modal').style.display = 'none';
        }
    </script>
</body>

</html>