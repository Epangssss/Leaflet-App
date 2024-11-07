<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term
$name = isset($_GET['name']) ? $_GET['name'] : '';

// Query to search locations using prepared statement
$stmt = $conn->prepare("SELECT id, name, latitude, longitude FROM locations WHERE name LIKE ?");
$searchTerm = '%' . $conn->real_escape_string($name) . '%';
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$locations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($locations);

$stmt->close();
$conn->close();
