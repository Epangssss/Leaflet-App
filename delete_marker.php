<?php
// Menghubungkan ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "leaflet"; // Ganti dengan nama database Anda

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]));
}

// Memeriksa apakah permintaan adalah DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Mendapatkan ID dari query string
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = isset($_DELETE['id']) ? intval($_DELETE['id']) : 0;

    // Validasi ID
    if ($id > 0) {
        // Query untuk menghapus marker berdasarkan ID
        $sql = "DELETE FROM locations WHERE id = ?";

        // Persiapkan statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // "i" menunjukkan integer

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Marker berhasil dihapus."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menghapus marker."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "ID tidak valid."]);
    }
}

$conn->close();
