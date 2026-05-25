USE pawerti;

-- Tabel Kategori
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Events
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul_event VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    tanggal_event DATE,
    lokasi VARCHAR(255),
    kategori_id INT,
    gambar VARCHAR(255),
    status ENUM('Aktif', 'Mendatang', 'Selesai') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
);

-- Data Contoh Kategori
INSERT INTO kategori (nama_kategori, deskripsi, icon) VALUES 
('Seni Pertunjukan', 'Wayang, tari klasik Jawa, dan pertunjukan seni lainnya', 'fa-theater-masks'),
('Workshop Budaya', 'Workshop batik, keris, dan kerajinan tradisional', 'fa-tools'),
('Kuliner Tradisional', 'Festival makanan dan minuman khas Jawa', 'fa-utensils');

-- Data Contoh Events
INSERT INTO events (judul_event, deskripsi, tanggal_event, lokasi, kategori_id, status) VALUES 
('Festival Reog Ponorogo', 'Pertunjukan kolosal Reog Ponorogo', '2026-06-20', 'Ponorogo', 1, 'Aktif'),
('Workshop Batik Tulis', 'Belajar membatik bersama maestro', '2026-07-10', 'Solo', 2, 'Mendatang'),
('Pasar Rakyat Surabaya', 'Pesta kuliner khas Jawa Timur', '2026-06-15', 'Surabaya', 3, 'Aktif');
