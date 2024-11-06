<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$dbname = "leaflet"; // Sesuaikan dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Mengecek apakah request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari request POST
    $id = isset($_POST['markerId']) ? intval($_POST['markerId']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Memvalidasi ID dan name
    if ($id > 0 && !empty($name)) {
        // Inisialisasi variabel untuk image_url
        $image_url = null;

        // Cek apakah ada file gambar yang diunggah
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];

            // Memeriksa apakah file yang diunggah memiliki ekstensi yang diperbolehkan
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Menghasilkan nama file unik untuk menghindari konflik nama
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadFileDir = 'uploads/';
                $dest_path = $uploadFileDir . $newFileName;

                // Pindahkan file yang diunggah ke folder tujuan
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $image_url = $dest_path; // Set image_url jika upload berhasil
                } else {
                    echo json_encode(["status" => "error", "message" => "Error moving the uploaded file."]);
                    exit;
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid file type. Only JPG, GIF, PNG, and JPEG are allowed."]);
                exit;
            }
        }

        // Query untuk memperbarui marker dengan deskripsi dan gambar (jika ada gambar baru)
        if ($image_url) {
            // Jika ada gambar baru, update kolom image_url
            $stmt = $conn->prepare("UPDATE locations SET name = ?, deskripsi = ?, image_url = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $description, $image_url, $id);
        } else {
            // Jika tidak ada gambar baru, hanya update name dan deskripsi
            $stmt = $conn->prepare("UPDATE locations SET name = ?, deskripsi = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $description, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Marker updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update marker."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid data provided."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
