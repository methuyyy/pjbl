<?php
include 'BACKEND/config.php';

$event_id = 37; // CHANGE THIS TO YOUR EVENT ID!

// Function to run query and show error
function runQuery($conn, $sql, $name)
{
    echo "<h3>Trying: $name</h3>";
    if ($conn->query($sql)) {
        echo "✅ Success!<br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }
}

// Delete old data first
runQuery($conn, "DELETE FROM event_benefits WHERE event_id = $event_id", "Delete old benefits");
runQuery($conn, "DELETE FROM event_rundown WHERE event_id = $event_id", "Delete old rundown");
runQuery($conn, "DELETE FROM event_speakers WHERE event_id = $event_id", "Delete old speakers");
runQuery($conn, "DELETE FROM event_faqs WHERE event_id = $event_id", "Delete old FAQs");
runQuery($conn, "DELETE FROM event_terms WHERE event_id = $event_id", "Delete old terms");
runQuery($conn, "DELETE FROM event_locations WHERE event_id = $event_id", "Delete old location");

echo "<hr>";

// Add benefits
runQuery($conn, "INSERT INTO event_benefits (event_id, icon, title, description) VALUES ($event_id, 'fa-gift', 'Souvenir Budaya', 'Dapatkan souvenir khas Nusantara')", "Add Benefit 1");
runQuery($conn, "INSERT INTO event_benefits (event_id, icon, title, description) VALUES ($event_id, 'fa-utensils', 'Makan Siang Tradisional', 'Cicipi makanan khas dari berbagai daerah')", "Add Benefit 2");
runQuery($conn, "INSERT INTO event_benefits (event_id, icon, title, description) VALUES ($event_id, 'fa-certificate', 'Sertifikat', 'Dapatkan sertifikat keikutsertaan')", "Add Benefit 3");

echo "<hr>";

// Add rundown (one by one)
runQuery($conn, "INSERT INTO event_rundown (event_id, jam_mulai, jam_selesai, title, description, urutan) VALUES ($event_id, '09:00:00', '09:30:00', 'Pembukaan', 'Sambutan dan pembukaan acara oleh ketua panitia', 1)", "Add Rundown 1");
runQuery($conn, "INSERT INTO event_rundown (event_id, jam_mulai, jam_selesai, title, description, urutan) VALUES ($event_id, '09:30:00', '11:00:00', 'Tari Tradisional', 'Pertunjukan tari dari berbagai suku di Indonesia', 2)", "Add Rundown 2");
runQuery($conn, "INSERT INTO event_rundown (event_id, jam_mulai, jam_selesai, title, description, urutan) VALUES ($event_id, '11:00:00', '12:00:00', 'Workshop Batik', 'Belajar membatik langsung dengan maestro', 3)", "Add Rundown 3");
runQuery($conn, "INSERT INTO event_rundown (event_id, jam_mulai, jam_selesai, title, description, urutan) VALUES ($event_id, '12:00:00', '13:00:00', 'Istirahat & Makan Siang', 'Waktu untuk makan dan beristirahat', 4)", "Add Rundown 4");
runQuery($conn, "INSERT INTO event_rundown (event_id, jam_mulai, jam_selesai, title, description, urutan) VALUES ($event_id, '13:00:00', '14:30:00', 'Musik Tradisional', 'Pertunjukan gamelan dan alat musik tradisional lainnya', 5)", "Add Rundown 5");
runQuery($conn, "INSERT INTO event_rundown (event_id, jam_mulai, jam_selesai, title, description, urutan) VALUES ($event_id, '14:30:00', '15:00:00', 'Penutupan', 'Penutupan acara dan pengumuman', 6)", "Add Rundown 6");

echo "<hr>";

// Add speakers
runQuery($conn, "INSERT INTO event_speakers (event_id, nama, jabatan, bio) VALUES ($event_id, 'Pak Suroto', 'Maestro Tari Tradisional', 'Pak Suroto telah berpengalaman lebih dari 30 tahun dalam dunia tari tradisional Jawa.')", "Add Speaker 1");
runQuery($conn, "INSERT INTO event_speakers (event_id, nama, jabatan, bio) VALUES ($event_id, 'Bu Sri', 'Pengrajin Batik', 'Bu Sri adalah pengrajin batik generasi ketiga dari Solo dengan pengalaman 25 tahun.')", "Add Speaker 2");

echo "<hr>";

// Add FAQs
runQuery($conn, "INSERT INTO event_faqs (event_id, question, answer) VALUES ($event_id, 'Apakah acara bisa diikuti oleh anak-anak?', 'Ya, acara ini cocok untuk semua usia. Anak-anak diwajibkan didampingi oleh orang tua.')", "Add FAQ 1");
runQuery($conn, "INSERT INTO event_faqs (event_id, question, answer) VALUES ($event_id, 'Apakah perlu membawa peralatan sendiri?', 'Tidak, semua peralatan workshop sudah disediakan oleh panitia.')", "Add FAQ 2");
runQuery($conn, "INSERT INTO event_faqs (event_id, question, answer) VALUES ($event_id, 'Apakah ada parkir?', 'Ya, tersedia area parkir yang luas di lokasi acara.')", "Add FAQ 3");

echo "<hr>";

// Add terms
runQuery($conn, "INSERT INTO event_terms (event_id, isi, urutan) VALUES ($event_id, 'Peserta diwajibkan hadir 15 menit sebelum acara dimulai.', 1)", "Add Term 1");
runQuery($conn, "INSERT INTO event_terms (event_id, isi, urutan) VALUES ($event_id, 'Tiket yang sudah dibeli tidak bisa dikembalikan.', 2)", "Add Term 2");
runQuery($conn, "INSERT INTO event_terms (event_id, isi, urutan) VALUES ($event_id, 'Panitia berhak mengubah jadwal tanpa pemberitahuan terlebih dahulu.', 3)", "Add Term 3");
runQuery($conn, "INSERT INTO event_terms (event_id, isi, urutan) VALUES ($event_id, 'Peserta diwajibkan menjaga kebersihan lokasi acara.', 4)", "Add Term 4");

echo "<hr>";

// Add location
runQuery($conn, "INSERT INTO event_locations (event_id, nama_tempat, alamat, maps_link, catatan) VALUES ($event_id, 'Taman Budaya Jakarta', 'Jl. Gatot Subroto No. 1, Jakarta Selatan', 'https://maps.google.com/?q=Taman+Budaya+Jakarta', 'Parkir tersedia, akses mudah dengan transportasi umum.')", "Add Location");

echo "<hr>";
echo "<br><a href='VIEWS/USER/detail.php?id=$event_id'>View Event Detail Page</a>";
