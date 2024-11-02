<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari POST
    $name = $_POST['name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $deskripsi = $_POST['deskripsi'] ?? ''; // Menyimpan deskripsi, jika tidak ada akan kosong

    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'leaflet');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Menyiapkan SQL untuk memasukkan data marker
    $sql = "INSERT INTO locations (name, latitude, longitude, deskripsi) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $latitude, $longitude, $deskripsi); // Mengikat parameter

    // Menjalankan query dan mengecek hasilnya
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
