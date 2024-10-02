<?php
// Menghubungkan ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda (biarkan kosong jika tidak ada)
$dbname = "leaflet"; // Ganti dengan nama database Anda

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]));
}

// Mendapatkan ID dari permintaan POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    // Query untuk menghapus entri berdasarkan ID
    $sql = "DELETE FROM locations WHERE id = $id"; // Ganti locations dengan nama tabel Anda

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID tidak valid"]);
}

$conn->close();
