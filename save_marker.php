<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$name = $_POST['name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Check if a file was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];

    if (in_array($fileExtension, $allowedfileExtensions)) {
        $uploadFileDir = 'uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Save the marker details into the database
            $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, image_url) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $latitude, $longitude, $dest_path);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Marker berhasil disimpan.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database insert failed.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error moving the uploaded file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, GIF, PNG, and JPEG are allowed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No image uploaded.']);
}

$conn->close();
