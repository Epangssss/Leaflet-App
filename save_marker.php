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

// Ambil data dari POST
$id = $_POST['id'] ?? null;  // ID akan diambil dari POST
$name = $_POST['name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$deskripsi = $_POST['deskripsi'] ?? '';
$kategori_id = $_POST['kategori'] ?? '';  // Mengambil kategori_id dari form

// Fungsi untuk menyimpan gambar, jika ada
function uploadImage($file)
{
    $fileTmpPath = $file['tmp_name'];
    $fileName = $file['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
    $uploadFileDir = 'uploads/';
    
    // Cek ekstensi file
    if (!in_array($fileExtension, $allowedfileExtensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, GIF, PNG, and JPEG are allowed.']);
        exit;
    }
    
    // Cek ukuran file (misalnya, maksimal 2MB)
    if ($file['size'] > 2097152) {
        echo json_encode(['status' => 'error', 'message' => 'File size exceeds the maximum limit of 2MB.']);
        exit;
    }
    
    // Tentukan path penyimpanan file
    $dest_path = $uploadFileDir . uniqid() . '-' . $fileName; // Rename file agar unik

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        return $dest_path;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error moving the uploaded file.']);
        exit;
    }
}

// Jika ada kategori, simpan ke dalam database
if ($id) {
    // Update marker jika `id` ada
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Jika ada gambar baru, unggah dan perbarui `image_url`
        $image_url = uploadImage($_FILES['image']);
        $stmt = $conn->prepare("UPDATE locations SET name = ?, latitude = ?, longitude = ?, image_url = ?, deskripsi = ?, kategori_id = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $latitude, $longitude, $image_url, $deskripsi, $kategori_id, $id);
    } else {
        // Jika tidak ada gambar baru, perbarui tanpa `image_url`
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
    // Tambah marker jika `id` tidak ada
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Jika ada gambar, unggah dan simpan dengan `image_url`
        $image_url = uploadImage($_FILES['image']);
        $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, image_url, deskripsi, kategori_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $latitude, $longitude, $image_url, $deskripsi, $kategori_id);
    } else {
        // Jika tidak ada gambar, simpan tanpa `image_url`
        $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, deskripsi, kategori_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $latitude, $longitude, $deskripsi, $kategori_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Marker berhasil disimpan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed: ' . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>
