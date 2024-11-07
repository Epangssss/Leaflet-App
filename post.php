<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memastikan data yang dibutuhkan ada di POST
    if (isset($_POST['name'], $_POST['latitude'], $_POST['longitude'], $_POST['kategori'])) {
        // Mengambil data dari POST
        $name = $_POST['name'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $deskripsi = $_POST['deskripsi'] ?? ''; // Menyimpan deskripsi, jika tidak ada akan kosong
        $kategori_id = $_POST['kategori']; // Mengambil kategori dari form

        // Validasi input
        if (empty($name) || empty($latitude) || empty($longitude) || empty($kategori_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Name, latitude, longitude, and category are required']);
            exit;
        }

        // Validasi latitude dan longitude
        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            echo json_encode(['status' => 'error', 'message' => 'Latitude and longitude must be numeric']);
            exit;
        }

        // Koneksi ke database
        $conn = new mysqli('localhost', 'root', '', 'leaflet');
        if ($conn->connect_error) {
            echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
            exit;
        }

        // Menyiapkan SQL untuk memasukkan data marker
        $sql = "INSERT INTO locations (name, latitude, longitude, deskripsi, kategori_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(['status' => 'error', 'message' => 'SQL statement preparation failed']);
            exit;
        }

        // Mengikat parameter
        $stmt->bind_param("sssss", $name, $latitude, $longitude, $deskripsi, $kategori_id); 

        // Menjalankan query dan mengecek hasilnya
        if ($stmt->execute()) {
            // Menyertakan ID dari marker yang baru saja dimasukkan
            $new_marker_id = $stmt->insert_id;
            echo json_encode(['status' => 'success', 'message' => 'Marker successfully added', 'marker_id' => $new_marker_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Required data not found']);
    }
}
?>
