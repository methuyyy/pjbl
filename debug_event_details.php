<?php
include 'BACKEND/config.php';

echo "<h1>All Existing Events:</h1>";
$query_all = mysqli_query($conn, "SELECT id, judul_event FROM events");
echo "<ul>";
while ($row = mysqli_fetch_assoc($query_all)) {
    echo "<li><a href='?event_id=" . $row['id'] . "'>Event ID " . $row['id'] . ": " . htmlspecialchars($row['judul_event']) . "</a></li>";
}
echo "</ul>";

// Get event_id from URL, default to 1
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 1;

echo "<h1>Debug Event Details for ID: $event_id</h1>";

// Fetch main event
$query = mysqli_query($conn, "SELECT e.*, k.nama_kategori FROM events e LEFT JOIN kategori k ON e.kategori_id = k.id WHERE e.id = $event_id");
$event = mysqli_fetch_assoc($query);
echo "<h2>Event Data:</h2>";
echo "<pre>";
print_r($event);
echo "</pre>";

// Fetch benefits
$benefits_query = mysqli_query($conn, "SELECT * FROM event_benefits WHERE event_id = $event_id");
$benefits = mysqli_fetch_all($benefits_query, MYSQLI_ASSOC);
echo "<h2>Benefits:</h2>";
echo "<pre>";
print_r($benefits);
echo "</pre>";

// Fetch rundown
$rundown_query = mysqli_query($conn, "SELECT * FROM event_rundown WHERE event_id = $event_id ORDER BY urutan ASC");
$rundown = mysqli_fetch_all($rundown_query, MYSQLI_ASSOC);
echo "<h2>Rundown:</h2>";
echo "<pre>";
print_r($rundown);
echo "</pre>";

// Fetch speakers
$speakers_query = mysqli_query($conn, "SELECT * FROM event_speakers WHERE event_id = $event_id");
$speakers = mysqli_fetch_all($speakers_query, MYSQLI_ASSOC);
echo "<h2>Speakers:</h2>";
echo "<pre>";
print_r($speakers);
echo "</pre>";

// Fetch FAQs
$faq_query = mysqli_query($conn, "SELECT * FROM event_faqs WHERE event_id = $event_id");
$faqs = mysqli_fetch_all($faq_query, MYSQLI_ASSOC);
echo "<h2>FAQs:</h2>";
echo "<pre>";
print_r($faqs);
echo "</pre>";

// Fetch terms
$terms_query = mysqli_query($conn, "SELECT * FROM event_terms WHERE event_id = $event_id ORDER BY urutan ASC");
$terms = mysqli_fetch_all($terms_query, MYSQLI_ASSOC);
echo "<h2>Terms:</h2>";
echo "<pre>";
print_r($terms);
echo "</pre>";

// Fetch location
$location_query = mysqli_query($conn, "SELECT * FROM event_locations WHERE event_id = $event_id LIMIT 1");
$location = mysqli_fetch_assoc($location_query);
echo "<h2>Location:</h2>";
echo "<pre>";
print_r($location);
echo "</pre>";

echo "<h2>Check if tables exist:</h2>";
$tables = ['event_benefits', 'event_rundown', 'event_speakers', 'event_faqs', 'event_terms', 'event_locations'];
foreach ($tables as $table) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    $exists = mysqli_num_rows($check) > 0;
    echo "$table: " . ($exists ? "✅ EXISTS" : "❌ MISSING") . "<br>";
}
