-- Tabel Event Benefits
CREATE TABLE IF NOT EXISTS event_benefits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    icon VARCHAR(100) DEFAULT '',
    title VARCHAR(255) DEFAULT '',
    description TEXT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Tabel Event Rundowns
CREATE TABLE IF NOT EXISTS event_rundowns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    urutan INT NOT NULL DEFAULT 0,
    waktu TIME,
    title VARCHAR(255) DEFAULT '',
    description TEXT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Tabel Event Speakers
CREATE TABLE IF NOT EXISTS event_speakers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    nama VARCHAR(255) DEFAULT '',
    pekerjaan VARCHAR(255) DEFAULT '',
    bio TEXT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Tabel Event FAQs
CREATE TABLE IF NOT EXISTS event_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    question TEXT,
    answer TEXT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Tabel Event Terms
CREATE TABLE IF NOT EXISTS event_terms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    term TEXT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Tabel Event Locations
CREATE TABLE IF NOT EXISTS event_locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    place_name VARCHAR(255) DEFAULT '',
    address TEXT,
    maps_url TEXT,
    note TEXT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);
