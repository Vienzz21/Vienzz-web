<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_sicaper";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Menangani jika ada error koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data yang dikirim dari frontend (berupa FormData)
$id = $_POST['id'];
$email = $_POST['email'];

// Handle file uploads if they exist
if (isset($_FILES['ijazahFile'])) {
    $ijazahFile = $_FILES['ijazahFile'];
    $ijazahFileName = $ijazahFile['name'];
    $ijazahTmpName = $ijazahFile['tmp_name'];
    $ijazahUploadPath = 'ijazahfoto/' . basename($ijazahFileName);

    if (!move_uploaded_file($ijazahTmpName, $ijazahUploadPath)) {
        $response = array("status" => "error", "message" => "Failed to move uploaded ijazah file.");
        echo json_encode($response);
        exit;
    }
}

if (isset($_FILES['transkripNilaiFile'])) {
    $transkripNilaiFile = $_FILES['transkripNilaiFile'];
    $transkripNilaiFileName = $transkripNilaiFile['name'];
    $transkripNilaiTmpName = $transkripNilaiFile['tmp_name'];
    $transkripNilaiUploadPath = 'transkripfoto/' . basename($transkripNilaiFileName);

    if (!move_uploaded_file($transkripNilaiTmpName, $transkripNilaiUploadPath)) {
        $response = array("status" => "error", "message" => "Failed to move uploaded transkrip nilai file.");
        echo json_encode($response);
        exit;
    }
}

if (isset($_FILES['suratSehatFile'])) {
    $suratSehatFile = $_FILES['suratSehatFile'];
    $suratSehatFileName = $suratSehatFile['name'];
    $suratSehatTmpName = $suratSehatFile['tmp_name'];
    $suratSehatUploadPath = 'sksfoto/' . basename($suratSehatFileName);

    if (!move_uploaded_file($suratSehatTmpName, $suratSehatUploadPath)) {
        $response = array("status" => "error", "message" => "Failed to move uploaded surat sehat file.");
        echo json_encode($response);
        exit;
    }
}

// SQL untuk memperbarui data berkas_pendukung
$sql = "UPDATE berkas_pendukung SET email='$email'";

// Tambahkan file ke SQL jika ada perubahan
if (isset($ijazahUploadPath)) {
    $sql .= ", ijazah='$ijazahUploadPath'";
}
if (isset($transkripNilaiUploadPath)) {
    $sql .= ", transkrip_nilai='$transkripNilaiUploadPath'";
}
if (isset($suratSehatUploadPath)) {
    $sql .= ", surat_sehat='$suratSehatUploadPath'";
}

$sql .= " WHERE id='$id'";

// Eksekusi SQL untuk memperbarui data
if ($conn->query($sql) === TRUE) {
    $response = array("status" => "success", "message" => "Data berhasil diperbarui.");
} else {
    $response = array("status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error);
}

// Menutup koneksi database
$conn->close();

// Mengembalikan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
