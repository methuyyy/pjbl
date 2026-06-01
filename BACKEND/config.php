<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pawerti';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi sederhana untuk membaca file .env
function loadEnv($path)
{
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip komentar
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse key=value
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Hilangkan tanda kutip jika ada
        if (preg_match('/^"(.*)"$/', $value, $matches)) {
            $value = $matches[1];
        } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
            $value = $matches[1];
        }

        // Define konstanta atau set environment variable
        if (!defined($name)) {
            define($name, $value);
        }
        putenv("$name=$value");
    }
    return true;
}

// Load file .env
loadEnv(__DIR__ . '/../.env');

// Default konfigurasi jika tidak ada di .env
defined('USE_FALLBACK') || define('USE_FALLBACK', true);
defined('USE_HUGGING_FACE') || define('USE_HUGGING_FACE', false);
defined('HUGGING_FACE_MODEL') || define('HUGGING_FACE_MODEL', 'mistralai/Mistral-7B-Instruct-v0.3');
defined('GEMINI_API_URL') || define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent');
