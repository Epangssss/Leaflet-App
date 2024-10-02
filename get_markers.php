<?php
include 'db_config.php'; // Koneksi database yang telah Anda buat

// Query untuk mengambil semua lokasi yang disimpan di database
$query = $db->query("SELECT * FROM locations");
$locations = $query->fetchAll(PDO::FETCH_ASSOC);

// Mengembalikan data sebagai JSON
header('Content-Type: application/json');
echo json_encode($locations);
