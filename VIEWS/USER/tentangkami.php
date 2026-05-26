<?php
session_start();
include '../../BACKEND/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawerti - Tentang Kami</title>
    <link rel="stylesheet" href="../../CSS/tentangkami.css">
</head>
<body>
 <?php include '../COMPONENTS/navbar.php'; ?>

    <!-- SECTION: TENTANG KAMI -->
    <section class="about-section">
        <div class="about-text">
            <p class="sub">gimana kita memulai?</p>
            <h3>Mimpi Kami Akan Melestarikan Warisan Budaya Jawa</h3>
            <p>
                Pawerti lahir dari kecintaan yang mendalam terhadap kekayaan budaya Jawa yang tak ternilai.
                Kami percaya bahwa setiap tradisi, setiap ritual, dan setiap tarian merupakan potret cermin 
                dari kebijaksanaan leluhur yang harus terus hidup. Dengan dedikasi dan semangat yang sama, 
                kami membangun sebuah platform digital untuk memudahkan siapa pun menjelajahi dan mengikuti 
                berbagai acara kebudayaan Jawa, serta berinteraksi dengan generasi kreatif. Misi kami adalah 
                menjadi jembatan antara masa lalu yang agung dan masa depan yang penuh inovasi, memastikan 
                warisan ini tetap abadi dan relevan bagi generasi mendatang.
            </p>
        </div>
        <div class="about-img">
            <img src="../../images/teamtententang.png" alt="Tim Pawerti">
        </div>
    </section>

    <!-- VISI MISI -->
    <section class="visi-misi">
        <div class="visi">
            <h4>Visi</h4>
            <p>
                Menjadi portal digital terdepan yang menghubungkan masyarakat global dengan kekayaan seni 
                dan budaya Jawa, serta menjadi sumber informasi utama untuk pelestarian dan perayaan 
                warisan budaya Jawa.
            </p>
        </div>
        <div class="misi">
            <h4>Misi</h4>
            <p>
                Kami berupaya menjadi jembatan antara kekayaan budaya Jawa dan masyarakat dunia dengan menyediakan 
                platform yang informatif dan mudah diakses. Misi kami adalah mendorong semangat dan komunitas 
                kreatif dalam menjaga, melestarikan, serta berkolaborasi untuk menjadikan warisan budaya Jawa 
                tetap hidup dan dapat diakses oleh generasi mendatang.
            </p>
        </div>
    </section>

    <!-- PARTISIPASI -->
    <section class="partisipasi" id="partisipasi">
        <h2>Partisipasi</h2>
        <div class="cards">
            <div class="card">
                <div class="icon">💃</div>
                <h3>Pelatihan Tari</h3>
                <p>Ikuti pelatihan menari tradisional dari para seniman Jawa terbaik.</p>
                <button>Gabung Pelatihan
                  <a href="loginbin.html"></a>
                </button>
            </div>
            <div class="card">
                <div class="icon">🎵</div>
                <h3>Kursus Musik</h3>
                <p>Pelajari musik tradisional Jawa seperti gamelan dan tembang.</p>
                <button>Mulai Belajar</button>
            </div>
            <div class="card">
                <div class="icon">🎨</div>
                <h3>Workshop Seni</h3>
                <p>Ikuti workshop seni rupa dan kerajinan khas budaya Jawa.</p>
                <button>Daftar Workshop</button>
            </div>
            <div class="card">
                <div class="icon">📖</div>
                <h3>Dokumentasi Budaya</h3>
                <p>Bagikan dokumentasi dan tulisan mengenai budaya Jawa.</p>
                <button>Kontribusi</button>
            </div>
        </div>
    </section>

     <!-- Footer -->
    <?php include '../COMPONENTS/footer.php'; ?>
</body>
</html>