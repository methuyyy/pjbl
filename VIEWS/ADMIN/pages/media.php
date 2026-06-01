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
    <title>Media Library</title>
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

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .media-item {
            background: #F7F9FC;
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }

        .media-item:hover {
            border-color: var(--primary);
        }

        .media-thumb {
            width: 100%;
            aspect-ratio: 1;
            background: #E0E5EF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #9FA6B2;
        }

        .media-info {
            padding: 10px;
        }

        .media-name {
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .media-meta {
            font-size: 11px;
            color: #9FA6B2;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <?php include '../components/sidebar.php'; ?>

    <main class="main">
        <?php include '../components/topbar.php'; ?>

        <div class="content">
            <div class="page-header">
                <div>
                    <div class="page-title">Media Library</div>
                    <div class="page-sub">Unggah dan kelola file media untuk event</div>
                </div>
                <button class="btn btn-primary"><i class="fas fa-cloud-upload-alt"></i> Unggah File</button>
            </div>
            <div class="card">
                <div class="media-grid">
                    <div class="media-item">
                        <div class="media-thumb"><i class="fas fa-image"></i></div>
                        <div class="media-info">
                            <div class="media-name">banner-event-1.jpg</div>
                            <div class="media-meta">1.2 MB • 2 hari lalu</div>
                        </div>
                    </div>
                    <div class="media-item">
                        <div class="media-thumb" style="color:#4CAF50;"><i class="fas fa-file-video"></i></div>
                        <div class="media-info">
                            <div class="media-name">teaser-festival.mp4</div>
                            <div class="media-meta">24.5 MB • 3 hari lalu</div>
                        </div>
                    </div>
                    <div class="media-item">
                        <div class="media-thumb"><i class="fas fa-image"></i></div>
                        <div class="media-info">
                            <div class="media-name">poster-artis.jpg</div>
                            <div class="media-meta">850 KB • 5 hari lalu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-nav a.nav-item');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === 'media.php') {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>