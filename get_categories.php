<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$dbname = "leaflet"; // Sesuaikan dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil kategori
$sql = "SELECT id_kategori, nama_kategori FROM kategori";
$result = $conn->query($sql);

// Cek apakah ada kategori
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Mengatur header agar respons berupa JSON
header('Content-Type: application/json');

// Mengembalikan data kategori dalam format JSON
echo json_encode($categories);

$conn->close();

