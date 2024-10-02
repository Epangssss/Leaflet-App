<?php
// Connect to the database
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "leaflet"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the ID and data from POST request
    $id = isset($_POST['markerId']) ? intval($_POST['markerId']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    // Validate ID and name
    if ($id > 0 && !empty($name)) {
        // Update marker name query
        $stmt = $conn->prepare("UPDATE locations SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Marker name updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update marker name."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid data provided."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
