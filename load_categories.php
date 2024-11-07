<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'leaflet');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Query untuk mengambil data kategori
$sql = "SELECT id_kategori, nama_kategori FROM kategori";
$result = $conn->query($sql);

// Menyusun data kategori dalam array
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($categories);

$conn->close();
?>
