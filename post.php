<?php
include 'db_config.php'; // Koneksi database

if (isset($_POST['name']) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $name = $_POST['name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Query untuk menyimpan data lokasi
    $stmt = $db->prepare("INSERT INTO locations (name, latitude, longitude) VALUES (?, ?, ?)");
    $result = $stmt->execute([$name, $latitude, $longitude]);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save location.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}
