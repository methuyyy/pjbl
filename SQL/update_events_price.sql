USE pawerti;

-- Menambahkan kolom harga ke tabel events
ALTER TABLE events ADD COLUMN harga DECIMAL(10, 2) DEFAULT 0.00 AFTER sisa_kursi;

-- Memperbarui data contoh dengan harga
UPDATE events SET harga = 50000.00 WHERE judul_event = 'Festival Reog Ponorogo';
UPDATE events SET harga = 150000.00 WHERE judul_event = 'Workshop Batik Tulis';
UPDATE events SET harga = 0.00 WHERE judul_event = 'Pasar Rakyat Surabaya';
