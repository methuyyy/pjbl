USE pawerti;

ALTER TABLE events 
ADD COLUMN total_kursi INT DEFAULT 0,
ADD COLUMN sisa_kursi INT DEFAULT 0,
ADD COLUMN gambar2 VARCHAR(255) DEFAULT NULL,
ADD COLUMN gambar3 VARCHAR(255) DEFAULT NULL;

-- Rename gambar to gambar1 for consistency if needed, but for now we can just use gambar as gambar1
ALTER TABLE events CHANGE COLUMN gambar gambar1 VARCHAR(255) DEFAULT NULL;
