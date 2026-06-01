<?php
require_once 'BACKEND/config.php';

$sqlFile = 'create_missing_tables.sql';
$sql = file_get_contents($sqlFile);

if ($conn->multi_query($sql)) {
    echo "Tabel berhasil dibuat!";
} else {
    echo "Error membuat tabel: " . $conn->error;
}

$conn->close();
?>
