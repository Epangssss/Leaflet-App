<?php
// Menghubungkan ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]));
}

// Memeriksa apakah permintaan adalah DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Mengambil data dari body permintaan
    parse_str(file_get_contents("php://input"), $data);
    $id = isset($data['id']) ? intval($data['id']) : 0;

    if ($id > 0) {
        $sql = "DELETE FROM locations WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(["status" => "success", "message" => "Marker berhasil dihapus."]);
            } else {
                echo json_encode(["status" => "error", "message" => "ID marker tidak valid."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menyiapkan pernyataan."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ID marker tidak valid."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Permintaan tidak valid."]);
}

$conn->close();
