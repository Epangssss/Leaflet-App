<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Ambil data dari POST
$id = $_POST['id'] ?? null;  // ID akan diambil dari POST
$name = $_POST['name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$deskripsi = $_POST['deskripsi'] ?? '';

// Fungsi untuk menyimpan gambar, jika ada
function uploadImage($file)
{
    $fileTmpPath = $file['tmp_name'];
    $fileName = $file['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
    $uploadFileDir = 'uploads/';
    $dest_path = $uploadFileDir . uniqid() . '-' . $fileName; // Rename file agar unik

    if (in_array($fileExtension, $allowedfileExtensions)) {
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            return $dest_path;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error moving the uploaded file.']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, GIF, PNG, and JPEG are allowed.']);
        exit;
    }
}

// Cek apakah operasi adalah edit atau tambah
if ($id) {
    // Update marker jika `id` ada
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Jika ada gambar baru, unggah dan perbarui `image_url`
        $image_url = uploadImage($_FILES['image']);
        $stmt = $conn->prepare("UPDATE locations SET name = ?, latitude = ?, longitude = ?, image_url = ?, deskripsi = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $latitude, $longitude, $image_url, $deskripsi, $id);
    } else {
        // Jika tidak ada gambar baru, perbarui tanpa `image_url`
        $stmt = $conn->prepare("UPDATE locations SET name = ?, latitude = ?, longitude = ?, deskripsi = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $latitude, $longitude, $deskripsi, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Marker berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    // Tambah marker jika `id` tidak ada
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Jika ada gambar, unggah dan simpan dengan `image_url`
        $image_url = uploadImage($_FILES['image']);
        $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, image_url, deskripsi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $latitude, $longitude, $image_url, $deskripsi);
    } else {
        // Jika tidak ada gambar, simpan tanpa `image_url`
        $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, deskripsi) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $latitude, $longitude, $deskripsi);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Marker berhasil disimpan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed: ' . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
