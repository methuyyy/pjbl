USE pawerti;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tambahkan akun admin default (password: admin123)
INSERT INTO admins (username, password, full_name) 
VALUES ('admin', '$2y$10$89Ew6YtQnB/jXfXG.t0vGOzX9U.uR0Z2Z2uY6S.7j8m4P6eF6rE8q', 'Administrator Pawerti')
ON DUPLICATE KEY UPDATE username=username;
