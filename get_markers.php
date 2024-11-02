<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil semua data marker termasuk deskripsi
$sql = "SELECT id, name, latitude, longitude, image_url, deskripsi FROM locations";
$result = $conn->query($sql);

$markers = array();
while ($row = $result->fetch_assoc()) {
    $markers[] = $row;
}

// Mengatur header untuk JSON
header('Content-Type: application/json');
echo json_encode($markers);

$conn->close();
