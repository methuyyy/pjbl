<?php
require_once 'config.php';

header('Content-Type: application/json');

// Memastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Method tidak diizinkan'
    ]);
    exit;
}

// Mendapatkan data dari request
$input = json_decode(file_get_contents('php://input'), true);
$judul = $input['judul'] ?? '';
$kategori = $input['kategori'] ?? '';
$lokasi = $input['lokasi'] ?? '';

if (empty($judul)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Judul event diperlukan'
    ]);
    exit;
}

// === FALLBACK LOKAL (GRATIS SEPENUHNYA, TANPA API) ===
if (defined('USE_FALLBACK') && USE_FALLBACK) {
    $deskripsi = generateLocalDescription($judul, $kategori, $lokasi);
    echo json_encode([
        'status' => 'success',
        'deskripsi' => $deskripsi
    ]);
    exit;
}

// Membuat prompt yang sama untuk kedua AI
$prompt = "Buatlah deskripsi event yang menarik dan profesional untuk event budaya Indonesia dengan detail berikut:\n";
$prompt .= "- Judul: $judul\n";
if ($kategori) {
    $prompt .= "- Kategori: $kategori\n";
}
if ($lokasi) {
    $prompt .= "- Lokasi: $lokasi\n";
}
$prompt .= "\nDeskripsi harus dalam bahasa Indonesia, ramah, dan maksimal 300 kata. Jangan gunakan format markdown atau heading. Gunakan bahasa yang natural dan menarik untuk calon peserta.";

// Pilih AI yang akan dipakai
$response = '';
if (defined('USE_HUGGING_FACE') && USE_HUGGING_FACE) {
    // === HUGGING FACE (GRATIS) ===
    if (HUGGING_FACE_API_KEY === 'YOUR_HUGGING_FACE_TOKEN_HERE') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi API token Hugging Face terlebih dahulu di config.php'
        ]);
        exit;
    }

    // Coba model yang lebih stabil (gunakan model yang lebih ringkas dulu)
    $apiUrl = 'https://api-inference.huggingface.co/models/HuggingFaceH4/zephyr-7b-beta';

    // Format prompt untuk Zephyr (sama dengan Mistral)
    $formattedPrompt = "<s>[INST] $prompt [/INST]";

    $data = [
        'inputs' => $formattedPrompt,
        'parameters' => [
            'max_new_tokens' => 300,
            'temperature' => 0.7,
            'top_p' => 0.95,
            'return_full_text' => false
        ]
    ];

    // Coba menggunakan cURL terlebih dahulu (lebih stabil)
    if (function_exists('curl_init')) {
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . HUGGING_FACE_API_KEY
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Nonaktifkan verifikasi SSL untuk debugging
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            echo json_encode([
                'status' => 'error',
                'message' => 'cURL Error: ' . $error
            ]);
            exit;
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            $decoded = json_decode($response, true);
            $errorMsg = isset($decoded['error']) ? $decoded['error'] : "HTTP $httpCode";
            echo json_encode([
                'status' => 'error',
                'message' => 'API Error: ' . $errorMsg
            ]);
            exit;
        }
    } else {
        // Fallback ke file_get_contents
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\nAuthorization: Bearer " . HUGGING_FACE_API_KEY . "\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'timeout' => 30
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ];
        $context = stream_context_create($options);
        $response = @file_get_contents($apiUrl, false, $context);

        if ($response === false) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal terhubung ke API (file_get_contents tidak diizinkan)'
            ]);
            exit;
        }
    }
} else {
    // === GEMINI ===
    if (GEMINI_API_KEY === 'YOUR_GEMINI_API_KEY_HERE') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi API key Gemini terlebih dahulu di config.php'
        ]);
        exit;
    }

    $apiUrl = GEMINI_API_URL . '?key=' . GEMINI_API_KEY;
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ];
    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $response = @file_get_contents($apiUrl, false, $context);

    if ($response === false) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal terhubung ke API'
        ]);
        exit;
    }
}

$result = json_decode($response, true);

// Parse respon sesuai AI yang dipilih
$deskripsi = '';
if (defined('USE_HUGGING_FACE') && USE_HUGGING_FACE) {
    if (isset($result[0]['generated_text'])) {
        $deskripsi = trim($result[0]['generated_text']);
    } elseif (isset($result['error'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Hugging Face Error: ' . $result['error']
        ]);
        exit;
    }
} else {
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $deskripsi = trim($result['candidates'][0]['content']['parts'][0]['text']);
    }
}

if ($deskripsi) {
    echo json_encode([
        'status' => 'success',
        'deskripsi' => $deskripsi
    ]);
} else {
    // Kalau gagal, coba fallback lokal sebagai last resort
    $deskripsi = generateLocalDescription($judul, $kategori, $lokasi);
    echo json_encode([
        'status' => 'success',
        'deskripsi' => $deskripsi
    ]);
}

// === FUNGSI GENERATOR DESKRIPSI LOKAL ===
function generateLocalDescription($judul, $kategori, $lokasi)
{
    $kategori = $kategori ?: 'Event Budaya';
    $lokasi = $lokasi ?: 'Lokasi yang nyaman dan strategis';

    // Template deskripsi yang bervariasi
    $templates = [
        "Selamat datang di $judul, sebuah $kategori yang penuh makna dan kebudayaan! Acara ini akan diselenggarakan di $lokasi, membawa nuansa budaya Indonesia yang kaya akan seni dan tradisi. Bersiaplah untuk merasakan pengalaman yang tidak akan terlupakan!",
        "$judul adalah $kategori yang wajib Anda datangi! Diadakan di $lokasi, acara ini menyuguhkan berbagai atraksi budaya yang menarik, mulai dari tari tradisional, musik gamelan, hingga kuliner khas Indonesia yang lezat!",
        "Ikuti keseruan $judul, $kategori paling dinanti tahun ini! Di $lokasi, Anda akan diajak menjelajahi keindahan warisan budaya Indonesia melalui berbagai aktivitas seru dan menginspirasi!"
    ];

    // Pilih template secara acak untuk variasi
    $selectedTemplate = $templates[array_rand($templates)];

    return $selectedTemplate;
}
