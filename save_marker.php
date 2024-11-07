<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaflet";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Ambil data dari POST dan lakukan sanitasi
$id = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$latitude = trim($_POST['latitude'] ?? '');
$longitude = trim($_POST['longitude'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$kategori_id = $_POST['kategori_id'] ?? '';

// Validasi data input
if (empty($name) || empty($latitude) || empty($longitude) || empty($kategori_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap. Pastikan semua kolom terisi.']);
    exit;
}

// Cek apakah kategori_id ada di tabel kategori
$kategoriCheck = $conn->prepare("SELECT COUNT(*) FROM kategori WHERE id_kategori = ?");
$kategoriCheck->bind_param("i", $kategori_id);
$kategoriCheck->execute();
$kategoriCheck->bind_result($isValidKategori);
$kategoriCheck->fetch();
$kategoriCheck->close();

// Debugging kategori_id yang diterima dan hasil cek kategori
error_log("Kategori ID yang diterima: " . $kategori_id);
error_log("Apakah kategori valid: " . $isValidKategori);

if ($isValidKategori == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Kategori tidak ditemukan. Pastikan memilih kategori yang valid.']);
    exit;
}

// Fungsi untuk mengunggah gambar, jika ada
function uploadImage($file)
{
    $fileTmpPath = $file['tmp_name'];
    $fileName = $file['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedFileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
    $uploadFileDir = 'uploads/';

    if (!in_array($fileExtension, $allowedFileExtensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, GIF, PNG, and JPEG are allowed.']);
        exit;
    }
    if ($file['size'] > 2097152) {
        echo json_encode(['status' => 'error', 'message' => 'File size exceeds the maximum limit of 2MB.']);
        exit;
    }

    $dest_path = $uploadFileDir . uniqid() . '-' . $fileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        return $dest_path;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error moving the uploaded file.']);
        exit;
    }
}

// Periksa apakah `id` ada (untuk update atau insert)
if ($id) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = uploadImage($_FILES['image']);
        $stmt = $conn->prepare("UPDATE locations SET name = ?, latitude = ?, longitude = ?, image_url = ?, deskripsi = ?, kategori_id = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $latitude, $longitude, $image_url, $deskripsi, $kategori_id, $id);
    } else {
        $stmt = $conn->prepare("UPDATE locations SET name = ?, latitude = ?, longitude = ?, deskripsi = ?, kategori_id = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $latitude, $longitude, $deskripsi, $kategori_id, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Marker berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = uploadImage($_FILES['image']);
        $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, image_url, deskripsi, kategori_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $latitude, $longitude, $image_url, $deskripsi, $kategori_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, deskripsi, kategori_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $latitude, $longitude, $deskripsi, $kategori_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Marker berhasil disimpan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed: ' . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
