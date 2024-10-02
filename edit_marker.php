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

// Memeriksa apakah permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan ID dan nama dari permintaan POST
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = isset($_POST['name']) ? $_POST['name'] : '';

    // Validasi ID dan nama
    if ($id > 0 && !empty($name)) {
        // Query untuk memperbarui entri berdasarkan ID
        $sql = "UPDATE locations SET name = ? WHERE id = ?";

        // Persiapkan statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $id); // "si" menunjukkan string dan integer

        // Eksekusi query
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Data berhasil diperbarui"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ID atau nama tidak valid"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode permintaan tidak valid."]);
}

// Tutup koneksi
$conn->close();
