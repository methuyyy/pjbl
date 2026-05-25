<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // LOG UNTUK DEBUGGING (Hapus setelah selesai)
    // error_log("Login Attempt - Email: " . $email . " Password: " . $password);

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email dan password wajib diisi.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, password, first_name FROM users WHERE email = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Cek jika password cocok dengan hash, atau (untuk development) jika password sama persis (plain text)
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            // Start session and store user info
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Login berhasil!', 
                'user' => ['first_name' => $user['first_name']]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Password salah.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email tidak terdaftar.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
