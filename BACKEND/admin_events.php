<?php
require_once 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add' || $action === 'update') {
        $judul_event = $_POST['judul_event'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $tanggal_event = $_POST['tanggal_event'] ?? null;
        $lokasi = $_POST['lokasi'] ?? '';
        $kategori_id = $_POST['kategori_id'] ?? null;
        $total_kursi = $_POST['total_kursi'] ?? 0;
        $sisa_kursi = $_POST['sisa_kursi'] ?? 0;
        $harga = $_POST['harga'] ?? 0;
        $status = $_POST['status'] ?? 'Aktif';
        $is_featured = $_POST['is_featured'] ?? 0;
        $featured_sub = $_POST['featured_sub'] ?? '';

        $baseDir = __DIR__ . '/../uploads/events/';
        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        $gambar1 = $_POST['existing_gambar1'] ?? '';
        $gambar2 = $_POST['existing_gambar2'] ?? '';
        $gambar3 = $_POST['existing_gambar3'] ?? '';

        for ($i = 1; $i <= 3; $i++) {
            $key = "gambar$i";
            if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$key];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                $filepath = $baseDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    ${"gambar$i"} = "uploads/events/$filename";
                }
            }
        }

        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO events (judul_event, deskripsi, tanggal_event, lokasi, kategori_id, total_kursi, sisa_kursi, harga, status, is_featured, featured_sub, gambar1, gambar2, gambar3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
                exit;
            }
            // Simpler type string - treat most as strings
            $stmt->bind_param('ssssiiidssssss', $judul_event, $deskripsi, $tanggal_event, $lokasi, $kategori_id, $total_kursi, $sisa_kursi, $harga, $status, $is_featured, $featured_sub, $gambar1, $gambar2, $gambar3);
            if ($stmt->execute()) {
                $event_id = $conn->insert_id;
                saveEventDetails($event_id, $_POST);
                echo json_encode(['status' => 'success', 'id' => $event_id]);
            } else {
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
            }
        } elseif ($action === 'update') {
            $event_id = $_POST['id'];
            $stmt = $conn->prepare("UPDATE events SET judul_event=?, deskripsi=?, tanggal_event=?, lokasi=?, kategori_id=?, total_kursi=?, sisa_kursi=?, harga=?, status=?, is_featured=?, featured_sub=?, gambar1=?, gambar2=?, gambar3=? WHERE id=?");
            if (!$stmt) {
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
                exit;
            }
            // Simpler type string - treat most as strings
            $stmt->bind_param('ssssiiidssssssi', $judul_event, $deskripsi, $tanggal_event, $lokasi, $kategori_id, $total_kursi, $sisa_kursi, $harga, $status, $is_featured, $featured_sub, $gambar1, $gambar2, $gambar3, $event_id);
            if ($stmt->execute()) {
                deleteEventDetails($event_id);
                saveEventDetails($event_id, $_POST);
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
            }
        }
    }
    exit;
}

if ($action === 'list') {
    $result = $conn->query("SELECT e.*, k.nama_kategori FROM events e LEFT JOIN kategori k ON e.kategori_id = k.id ORDER BY e.id DESC");
    $events = [];
    while ($row = $result->fetch_assoc()) $events[] = $row;
    echo json_encode(['status' => 'success', 'data' => $events]);
    exit;
}

if ($action === 'get') {
    $id = $_GET['id'];
    $event = $conn->query("SELECT * FROM events WHERE id = $id")->fetch_assoc();
    if (!$event) die(json_encode(['status' => 'error', 'message' => 'Event not found']));

    $benefits = $conn->query("SELECT * FROM event_benefits WHERE event_id = $id")->fetch_all(MYSQLI_ASSOC);
    $rundowns = $conn->query("SELECT * FROM event_rundown WHERE event_id = $id ORDER BY urutan")->fetch_all(MYSQLI_ASSOC);
    $speakers = $conn->query("SELECT * FROM event_speakers WHERE event_id = $id")->fetch_all(MYSQLI_ASSOC);
    $faqs = $conn->query("SELECT * FROM event_faqs WHERE event_id = $id")->fetch_all(MYSQLI_ASSOC);
    $terms = $conn->query("SELECT * FROM event_terms WHERE event_id = $id")->fetch_all(MYSQLI_ASSOC);
    $location = $conn->query("SELECT * FROM event_locations WHERE event_id = $id")->fetch_assoc();
    echo json_encode([
        'status' => 'success',
        'data' => [
            'event' => $event,
            'benefits' => $benefits,
            'rundowns' => $rundowns,
            'speakers' => $speakers,
            'faqs' => $faqs,
            'terms' => $terms,
            'location' => $location
        ]
    ]);
    exit;
}

if ($action === 'delete') {
    $id = $_GET['id'];
    if ($conn->query("DELETE FROM events WHERE id = $id")) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    exit;
}

function saveEventDetails($event_id, $data)
{
    global $conn;

    if (isset($data['benefit_title'])) {
        foreach ($data['benefit_title'] as $i => $title) {
            if (!empty($title)) {
                $icon = $conn->real_escape_string($data['benefit_icon'][$i] ?? '');
                $title = $conn->real_escape_string($title);
                $desc = $conn->real_escape_string($data['benefit_desc'][$i] ?? '');
                $conn->query("INSERT INTO event_benefits (event_id, icon, title, description) VALUES ($event_id, '$icon', '$title', '$desc')");
            }
        }
    }

    if (isset($data['rundown_title'])) {
        foreach ($data['rundown_title'] as $i => $title) {
            if (!empty($title)) {
                $waktu = $data['rundown_waktu'][$i] ?? '00:00:00';
                $title = $conn->real_escape_string($title);
                $desc = $conn->real_escape_string($data['rundown_desc'][$i] ?? '');
                // Add jam_selesai with default value (1 hour later)
                $jam_selesai = date('H:i:s', strtotime($waktu) + 3600);
                $conn->query("INSERT INTO event_rundown (event_id, jam_mulai, jam_selesai, title, description, urutan) VALUES ($event_id, '$waktu', '$jam_selesai', '$title', '$desc', $i)");
            }
        }
    }

    if (isset($data['speaker_name'])) {
        foreach ($data['speaker_name'] as $i => $name) {
            if (!empty($name)) {
                $name = $conn->real_escape_string($name);
                $job = $conn->real_escape_string($data['speaker_job'][$i] ?? '');
                $bio = $conn->real_escape_string($data['speaker_bio'][$i] ?? '');
                $conn->query("INSERT INTO event_speakers (event_id, nama, jabatan, bio) VALUES ($event_id, '$name', '$job', '$bio')");
            }
        }
    }

    if (isset($data['faq_question'])) {
        foreach ($data['faq_question'] as $i => $question) {
            if (!empty($question)) {
                $question = $conn->real_escape_string($question);
                $answer = $conn->real_escape_string($data['faq_answer'][$i] ?? '');
                $conn->query("INSERT INTO event_faqs (event_id, question, answer) VALUES ($event_id, '$question', '$answer')");
            }
        }
    }

    if (isset($data['term_text'])) {
        foreach ($data['term_text'] as $i => $term) {
            if (!empty($term)) {
                $term = $conn->real_escape_string($term);
                $conn->query("INSERT INTO event_terms (event_id, isi, urutan) VALUES ($event_id, '$term', $i)");
            }
        }
    }

    $nama_tempat = $conn->real_escape_string($data['location_name'] ?? '');
    $alamat = $conn->real_escape_string($data['location_address'] ?? '');
    $maps_link = $conn->real_escape_string($data['location_maps'] ?? '');
    $catatan = $conn->real_escape_string($data['location_note'] ?? '');

    $check = $conn->query("SELECT id FROM event_locations WHERE event_id = $event_id")->num_rows;
    if ($check > 0) {
        $conn->query("UPDATE event_locations SET nama_tempat='$nama_tempat', alamat='$alamat', maps_link='$maps_link', catatan='$catatan' WHERE event_id=$event_id");
    } else {
        $conn->query("INSERT INTO event_locations (event_id, nama_tempat, alamat, maps_link, catatan) VALUES ($event_id, '$nama_tempat', '$alamat', '$maps_link', '$catatan')");
    }
}

function deleteEventDetails($event_id)
{
    global $conn;
    $conn->query("DELETE FROM event_benefits WHERE event_id = $event_id");
    $conn->query("DELETE FROM event_rundown WHERE event_id = $event_id");
    $conn->query("DELETE FROM event_speakers WHERE event_id = $event_id");
    $conn->query("DELETE FROM event_faqs WHERE event_id = $event_id");
    $conn->query("DELETE FROM event_terms WHERE event_id = $event_id");
}
