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

// Memeriksa apakah permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan ID dan data dari permintaan POST
    $id = isset($_POST['markerId']) ? intval($_POST['markerId']) : 0;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';

    // Validasi ID dan data lainnya
    if ($id > 0 && !empty($name) && !empty($latitude) && !empty($longitude)) {
        // Query untuk memperbarui entri berdasarkan ID
        $sql = "UPDATE locations SET name = ?, latitude = ?, longitude = ? WHERE id = ?";

        // Persiapkan statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $name, $latitude, $longitude, $id); // "ssii" menunjukkan 2 string dan 2 integer

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Marker berhasil diperbarui."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui marker."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "ID atau data marker tidak valid."]);
    }
}

$conn->close();
