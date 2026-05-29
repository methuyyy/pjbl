<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access.'
    ]);
    exit;
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? 'list';


/* =====================================================
   LIST EVENT
===================================================== */

if ($action === 'list') {

    $result = $conn->query("
        SELECT e.*, k.nama_kategori
        FROM events e
        LEFT JOIN kategori k
        ON e.kategori_id = k.id
        ORDER BY e.id DESC
    ");

    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $events
    ]);
}


/* =====================================================
   GET SINGLE EVENT + DETAIL
===================================================== */

elseif ($action === 'get' && isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $stmt = $conn->prepare("
        SELECT e.*, k.nama_kategori
        FROM events e
        LEFT JOIN kategori k
        ON e.kategori_id = k.id
        WHERE e.id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $event = $stmt->get_result()->fetch_assoc();

    if (!$event) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Event tidak ditemukan'
        ]);
        exit;
    }


    /* =========================
       BENEFITS
    ========================= */

    $benefits = [];

    $benefitQuery = $conn->query("
        SELECT *
        FROM event_benefits
        WHERE event_id = '$id'
        ORDER BY id ASC
    ");

    while ($row = $benefitQuery->fetch_assoc()) {
        $benefits[] = $row;
    }


    /* =========================
       RUNDOWN
    ========================= */

    $rundowns = [];

    $rundownQuery = $conn->query("
        SELECT *
        FROM event_rundowns
        WHERE event_id = '$id'
        ORDER BY urutan ASC
    ");

    while ($row = $rundownQuery->fetch_assoc()) {
        $rundowns[] = $row;
    }


    /* =========================
       SPEAKERS
    ========================= */

    $speakers = [];

    $speakerQuery = $conn->query("
        SELECT *
        FROM event_speakers
        WHERE event_id = '$id'
        ORDER BY id ASC
    ");

    while ($row = $speakerQuery->fetch_assoc()) {
        $speakers[] = $row;
    }


    /* =========================
       FAQ
    ========================= */

    $faqs = [];

    $faqQuery = $conn->query("
        SELECT *
        FROM event_faqs
        WHERE event_id = '$id'
        ORDER BY id ASC
    ");

    while ($row = $faqQuery->fetch_assoc()) {
        $faqs[] = $row;
    }


    /* =========================
       TERMS
    ========================= */

    $terms = [];

    $termQuery = $conn->query("
        SELECT *
        FROM event_terms
        WHERE event_id = '$id'
        ORDER BY id ASC
    ");

    while ($row = $termQuery->fetch_assoc()) {
        $terms[] = $row;
    }


    /* =========================
       LOCATION
    ========================= */

    $location = null;

    $locationQuery = $conn->query("
        SELECT *
        FROM event_locations
        WHERE event_id = '$id'
        LIMIT 1
    ");

    if ($locationQuery->num_rows > 0) {
        $location = $locationQuery->fetch_assoc();
    }


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
}


/* =====================================================
   ADD EVENT
===================================================== */

elseif ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = $_POST['judul_event'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal_event'];
    $lokasi = $_POST['lokasi'];
    $kategori_id = intval($_POST['kategori_id']);
    $status = $_POST['status'];

    $total_kursi = intval($_POST['total_kursi'] ?? 0);
    $sisa_kursi = intval($_POST['sisa_kursi'] ?? 0);
    $harga = floatval($_POST['harga'] ?? 0);

    $is_featured = intval($_POST['is_featured'] ?? 0);
    $featured_sub = $_POST['featured_sub'] ?? '';


    /* =========================
       FEATURED
    ========================= */

    if ($is_featured === 1) {
        $conn->query("UPDATE events SET is_featured = 0");
    }


    /* =========================
       UPLOAD IMAGE
    ========================= */

    $images = ['', '', ''];

    for ($i = 1; $i <= 3; $i++) {

        if (
            isset($_FILES['gambar' . $i]) &&
            $_FILES['gambar' . $i]['error'] === UPLOAD_ERR_OK
        ) {

            $ext = strtolower(pathinfo(
                $_FILES['gambar' . $i]['name'],
                PATHINFO_EXTENSION
            ));

            $newName = md5(
                time() .
                $_FILES['gambar' . $i]['name'] .
                $i
            ) . '.' . $ext;

            if (
                move_uploaded_file(
                    $_FILES['gambar' . $i]['tmp_name'],
                    '../images/storage/' . $newName
                )
            ) {
                $images[$i - 1] = $newName;
            }
        }
    }


    /* =========================
       INSERT EVENT
    ========================= */

    $stmt = $conn->prepare("
        INSERT INTO events (
            judul_event,
            deskripsi,
            tanggal_event,
            lokasi,
            kategori_id,
            status,
            gambar1,
            gambar2,
            gambar3,
            total_kursi,
            sisa_kursi,
            harga,
            is_featured,
            featured_sub
        )
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmt->bind_param(
        "ssssissssiiiis",
        $judul,
        $deskripsi,
        $tanggal,
        $lokasi,
        $kategori_id,
        $status,
        $images[0],
        $images[1],
        $images[2],
        $total_kursi,
        $sisa_kursi,
        $harga,
        $is_featured,
        $featured_sub
    );


    if ($stmt->execute()) {

        $event_id = $conn->insert_id;


        /* =========================
           BENEFITS
        ========================= */

        if (isset($_POST['benefit_title'])) {

            foreach ($_POST['benefit_title'] as $key => $title) {

                $icon = $_POST['benefit_icon'][$key] ?? '';
                $desc = $_POST['benefit_desc'][$key] ?? '';

                $insertBenefit = $conn->prepare("
                    INSERT INTO event_benefits (
                        event_id,
                        icon,
                        title,
                        description
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $insertBenefit->bind_param(
                    "isss",
                    $event_id,
                    $icon,
                    $title,
                    $desc
                );

                $insertBenefit->execute();
            }
        }


        /* =========================
           RUNDOWNS
        ========================= */

        if (isset($_POST['rundown_time'])) {

            foreach ($_POST['rundown_time'] as $key => $time) {

                $label = $_POST['rundown_title'][$key] ?? '';
                $desc = $_POST['rundown_desc'][$key] ?? '';
                $urutan = $key + 1;

                $insertRundown = $conn->prepare("
                    INSERT INTO event_rundowns (
                        event_id,
                        urutan,
                        waktu,
                        title,
                        description
                    )
                    VALUES (?, ?, ?, ?, ?)
                ");

                $insertRundown->bind_param(
                    "iisss",
                    $event_id,
                    $urutan,
                    $time,
                    $label,
                    $desc
                );

                $insertRundown->execute();
            }
        }


        /* =========================
           SPEAKERS
        ========================= */

        if (isset($_POST['speaker_name'])) {

            foreach ($_POST['speaker_name'] as $key => $name) {

                $job = $_POST['speaker_job'][$key] ?? '';
                $bio = $_POST['speaker_bio'][$key] ?? '';

                $insertSpeaker = $conn->prepare("
                    INSERT INTO event_speakers (
                        event_id,
                        nama,
                        pekerjaan,
                        bio
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $insertSpeaker->bind_param(
                    "isss",
                    $event_id,
                    $name,
                    $job,
                    $bio
                );

                $insertSpeaker->execute();
            }
        }


        /* =========================
           FAQ
        ========================= */

        if (isset($_POST['faq_question'])) {

            foreach ($_POST['faq_question'] as $key => $question) {

                $answer = $_POST['faq_answer'][$key] ?? '';

                $insertFaq = $conn->prepare("
                    INSERT INTO event_faqs (
                        event_id,
                        question,
                        answer
                    )
                    VALUES (?, ?, ?)
                ");

                $insertFaq->bind_param(
                    "iss",
                    $event_id,
                    $question,
                    $answer
                );

                $insertFaq->execute();
            }
        }


        /* =========================
           TERMS
        ========================= */

        if (isset($_POST['term_text'])) {

            foreach ($_POST['term_text'] as $term) {

                $insertTerm = $conn->prepare("
                    INSERT INTO event_terms (
                        event_id,
                        term
                    )
                    VALUES (?, ?)
                ");

                $insertTerm->bind_param(
                    "is",
                    $event_id,
                    $term
                );

                $insertTerm->execute();
            }
        }


        /* =========================
           LOCATION DETAIL
        ========================= */

        $location_name = $_POST['location_name'] ?? '';
        $location_address = $_POST['location_address'] ?? '';
        $location_maps = $_POST['location_maps'] ?? '';
        $location_note = $_POST['location_note'] ?? '';

        $insertLocation = $conn->prepare("
            INSERT INTO event_locations (
                event_id,
                place_name,
                address,
                maps_url,
                note
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $insertLocation->bind_param(
            "issss",
            $event_id,
            $location_name,
            $location_address,
            $location_maps,
            $location_note
        );

        $insertLocation->execute();


        echo json_encode([
            'status' => 'success',
            'message' => 'Event berhasil ditambahkan.'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menambahkan event.'
        ]);
    }
}


/* =====================================================
   UPDATE EVENT
===================================================== */

elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);

    $judul = $_POST['judul_event'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal_event'];
    $lokasi = $_POST['lokasi'];
    $kategori_id = intval($_POST['kategori_id']);
    $status = $_POST['status'];

    $total_kursi = intval($_POST['total_kursi'] ?? 0);
    $sisa_kursi = intval($_POST['sisa_kursi'] ?? 0);
    $harga = floatval($_POST['harga'] ?? 0);

    $is_featured = intval($_POST['is_featured'] ?? 0);
    $featured_sub = $_POST['featured_sub'] ?? '';


    if ($is_featured === 1) {
        $conn->query("UPDATE events SET is_featured = 0");
    }


    $sql = "
        UPDATE events
        SET
            judul_event=?,
            deskripsi=?,
            tanggal_event=?,
            lokasi=?,
            kategori_id=?,
            status=?,
            total_kursi=?,
            sisa_kursi=?,
            harga=?,
            is_featured=?,
            featured_sub=?
    ";

    $params = [
        $judul,
        $deskripsi,
        $tanggal,
        $lokasi,
        $kategori_id,
        $status,
        $total_kursi,
        $sisa_kursi,
        $harga,
        $is_featured,
        $featured_sub
    ];

    $types = "ssssisiiiis";


    for ($i = 1; $i <= 3; $i++) {

        if (
            isset($_FILES['gambar' . $i]) &&
            $_FILES['gambar' . $i]['error'] === UPLOAD_ERR_OK
        ) {

            $ext = strtolower(pathinfo(
                $_FILES['gambar' . $i]['name'],
                PATHINFO_EXTENSION
            ));

            $newName = md5(
                time() .
                $_FILES['gambar' . $i]['name'] .
                $i
            ) . '.' . $ext;

            if (
                move_uploaded_file(
                    $_FILES['gambar' . $i]['tmp_name'],
                    '../images/storage/' . $newName
                )
            ) {

                $sql .= ", gambar$i=?";
                $params[] = $newName;
                $types .= "s";
            }
        }
    }


    $sql .= " WHERE id=?";

    $params[] = $id;
    $types .= "i";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);


    if ($stmt->execute()) {


        /* =========================
           DELETE OLD DETAILS
        ========================= */

        $conn->query("DELETE FROM event_benefits WHERE event_id = '$id'");
        $conn->query("DELETE FROM event_rundowns WHERE event_id = '$id'");
        $conn->query("DELETE FROM event_speakers WHERE event_id = '$id'");
        $conn->query("DELETE FROM event_faqs WHERE event_id = '$id'");
        $conn->query("DELETE FROM event_terms WHERE event_id = '$id'");
        $conn->query("DELETE FROM event_locations WHERE event_id = '$id'");


        /* =========================
           INSERT NEW BENEFITS
        ========================= */

        if (isset($_POST['benefit_title'])) {

            foreach ($_POST['benefit_title'] as $key => $title) {

                $icon = $_POST['benefit_icon'][$key] ?? '';
                $desc = $_POST['benefit_desc'][$key] ?? '';

                $insertBenefit = $conn->prepare("
                    INSERT INTO event_benefits (
                        event_id,
                        icon,
                        title,
                        description
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $insertBenefit->bind_param(
                    "isss",
                    $id,
                    $icon,
                    $title,
                    $desc
                );

                $insertBenefit->execute();
            }
        }


        /* =========================
           INSERT NEW RUNDOWN
        ========================= */

        if (isset($_POST['rundown_time'])) {

            foreach ($_POST['rundown_time'] as $key => $time) {

                $label = $_POST['rundown_title'][$key] ?? '';
                $desc = $_POST['rundown_desc'][$key] ?? '';
                $urutan = $key + 1;

                $insertRundown = $conn->prepare("
                    INSERT INTO event_rundowns (
                        event_id,
                        urutan,
                        waktu,
                        title,
                        description
                    )
                    VALUES (?, ?, ?, ?, ?)
                ");

                $insertRundown->bind_param(
                    "iisss",
                    $id,
                    $urutan,
                    $time,
                    $label,
                    $desc
                );

                $insertRundown->execute();
            }
        }


        /* =========================
           INSERT NEW SPEAKERS
        ========================= */

        if (isset($_POST['speaker_name'])) {

            foreach ($_POST['speaker_name'] as $key => $name) {

                $job = $_POST['speaker_job'][$key] ?? '';
                $bio = $_POST['speaker_bio'][$key] ?? '';

                $insertSpeaker = $conn->prepare("
                    INSERT INTO event_speakers (
                        event_id,
                        nama,
                        pekerjaan,
                        bio
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $insertSpeaker->bind_param(
                    "isss",
                    $id,
                    $name,
                    $job,
                    $bio
                );

                $insertSpeaker->execute();
            }
        }


        /* =========================
           INSERT FAQ
        ========================= */

        if (isset($_POST['faq_question'])) {

            foreach ($_POST['faq_question'] as $key => $question) {

                $answer = $_POST['faq_answer'][$key] ?? '';

                $insertFaq = $conn->prepare("
                    INSERT INTO event_faqs (
                        event_id,
                        question,
                        answer
                    )
                    VALUES (?, ?, ?)
                ");

                $insertFaq->bind_param(
                    "iss",
                    $id,
                    $question,
                    $answer
                );

                $insertFaq->execute();
            }
        }


        /* =========================
           INSERT TERMS
        ========================= */

        if (isset($_POST['term_text'])) {

            foreach ($_POST['term_text'] as $term) {

                $insertTerm = $conn->prepare("
                    INSERT INTO event_terms (
                        event_id,
                        term
                    )
                    VALUES (?, ?)
                ");

                $insertTerm->bind_param(
                    "is",
                    $id,
                    $term
                );

                $insertTerm->execute();
            }
        }


        /* =========================
           LOCATION
        ========================= */

        $location_name = $_POST['location_name'] ?? '';
        $location_address = $_POST['location_address'] ?? '';
        $location_maps = $_POST['location_maps'] ?? '';
        $location_note = $_POST['location_note'] ?? '';

        $insertLocation = $conn->prepare("
            INSERT INTO event_locations (
                event_id,
                place_name,
                address,
                maps_url,
                note
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $insertLocation->bind_param(
            "issss",
            $id,
            $location_name,
            $location_address,
            $location_maps,
            $location_note
        );

        $insertLocation->execute();


        echo json_encode([
            'status' => 'success',
            'message' => 'Event berhasil diperbarui.'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal memperbarui event.'
        ]);
    }
}


/* =====================================================
   GENERATE AI
===================================================== */

elseif (
    $action === 'generate_ai' &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {

    $data = json_decode(file_get_contents("php://input"), true);

    $judul = $data['judul'] ?? '';

    if (empty($judul)) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Judul kosong'
        ]);

        exit;
    }


    $apiKey = "API_KEY_BARU_KAMU";

    $prompt = "Buatkan deskripsi event yang menarik dan profesional berdasarkan judul berikut: $judul";


    $postData = [
        "model" => "openai/gpt-3.5-turbo",
        "messages" => [
            [
                "role" => "user",
                "content" => $prompt
            ]
        ]
    ];


    $ch = curl_init("https://openrouter.ai/api/v1/chat/completions");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $apiKey,
        "Content-Type: application/json",
        "HTTP-Referer: http://localhost",
        "X-Title: Pawerti"
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($ch);


    if (curl_errno($ch)) {

        echo json_encode([
            'status' => 'error',
            'message' => curl_error($ch)
        ]);

        exit;
    }


    curl_close($ch);

    $result = json_decode($response, true);


    if (isset($result['choices'][0]['message']['content'])) {

        $text = $result['choices'][0]['message']['content'];

        echo json_encode([
            'status' => 'success',
            'result' => $text
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'response' => $result
        ]);
    }
}


/* =====================================================
   DELETE EVENT
===================================================== */

elseif ($action === 'delete' && isset($_GET['id'])) {

    $id = intval($_GET['id']);


    $conn->query("DELETE FROM event_benefits WHERE event_id = '$id'");
    $conn->query("DELETE FROM event_rundowns WHERE event_id = '$id'");
    $conn->query("DELETE FROM event_speakers WHERE event_id = '$id'");
    $conn->query("DELETE FROM event_faqs WHERE event_id = '$id'");
    $conn->query("DELETE FROM event_terms WHERE event_id = '$id'");
    $conn->query("DELETE FROM event_locations WHERE event_id = '$id'");


    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");

    $stmt->bind_param("i", $id);


    if ($stmt->execute()) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Event berhasil dihapus.'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menghapus event.'
        ]);
    }
}


$conn->close();
?>