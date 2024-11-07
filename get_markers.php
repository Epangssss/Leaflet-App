<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    // Jika ada ID yang dikirimkan, ambil data marker berdasarkan ID
    $id = intval($_GET['id']);
    // Query dengan JOIN untuk mengambil nama kategori
    $sql = "SELECT l.id, l.name, l.latitude, l.longitude, l.image_url, l.deskripsi, k.nama_kategori 
            FROM locations l 
            LEFT JOIN kategori k ON l.kategori_id = k.id_kategori 
            WHERE l.id = ?";
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
    // Ambil semua marker dengan kategori
    $sql = "SELECT l.id, l.name, l.latitude, l.longitude, l.deskripsi, l.image_url, k.nama_kategori 
            FROM locations l 
            LEFT JOIN kategori k ON l.kategori_id = k.id_kategori";
    $result = $conn->query($sql);
    
    $locations = [];
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
    
    echo json_encode($locations);
}

$conn->close();
?>
