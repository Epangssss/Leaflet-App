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

// Ambil data dari form
$name = $_POST['name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$deskripsi = $_POST['deskripsi'] ?? ''; // Menggunakan deskripsi jika ada, kosong jika tidak
$kategori = $_POST['kategori']; // Kategori yang dipilih

// Cek kategori dan ambil id_kategori dari tabel kategori
$query = "SELECT id_kategori FROM kategori WHERE nama_kategori = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $kategori);
$stmt->execute();
$stmt->bind_result($kategori_id);
$stmt->fetch();
$stmt->close();

// Jika kategori ditemukan
if ($kategori_id) {
    // Cek apakah file diunggah
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
                // Simpan detail marker ke dalam database termasuk kategori_id
                $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, image_url, deskripsi, kategori_id) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $name, $latitude, $longitude, $dest_path, $deskripsi, $kategori_id);
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
        // Jika tidak ada gambar yang diunggah, simpan data tanpa `image_url`
        $stmt = $conn->prepare("INSERT INTO locations (name, latitude, longitude, deskripsi, kategori_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $latitude, $longitude, $deskripsi, $kategori_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Marker berhasil disimpan tanpa gambar.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database insert failed.']);
        }
        $stmt->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Kategori tidak ditemukan.']);
}

$conn->close();
?>
