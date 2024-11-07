<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Ambil kategori dari tabel 'kategori'
$categoryQuery = "SELECT id_kategori, nama_kategori FROM kategori";
$categoryResult = $conn->query($categoryQuery);

$categories = [];
if ($categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Ambil marker dari tabel 'locations' dengan kategori_id
$markerQuery = "SELECT id, name, latitude, longitude, kategori_id FROM locations";
$markerResult = $conn->query($markerQuery);

$markers = [];
if ($markerResult->num_rows > 0) {
    while ($row = $markerResult->fetch_assoc()) {
        $markers[] = $row;
    }
}

// Kirimkan data kategori dan marker dalam format JSON
header('Content-Type: application/json');
echo json_encode(['categories' => $categories, 'markers' => $markers]);

$conn->close();

