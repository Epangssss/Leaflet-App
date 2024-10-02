<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, latitude, longitude, image_url FROM locations";
$result = $conn->query($sql);

$markers = array();
while ($row = $result->fetch_assoc()) {
    $markers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($markers);

$conn->close();
