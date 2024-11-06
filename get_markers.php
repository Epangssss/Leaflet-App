<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    // Jika ada ID yang dikirimkan, ambil data marker berdasarkan ID
    $id = intval($_GET['id']);
    $sql = "SELECT id, name, latitude, longitude, image_url, deskripsi FROM locations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["status" => "error", "message" => "Marker not found."]);
    }
} else {
    // Ambil semua marker jika tidak ada ID
    $sql = "SELECT id, name, latitude, longitude, image_url, deskripsi FROM locations";
    $result = $conn->query($sql);

    $markers = array();
    while ($row = $result->fetch_assoc()) {
        $markers[] = $row;
    }

    echo json_encode($markers);
}



$conn->close();
