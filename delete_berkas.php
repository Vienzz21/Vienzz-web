<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_sicaper";

// Mengambil data yang dikirim melalui metode POST
$data = json_decode(file_get_contents("php://input"));

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Menangani jika ada error koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data dari objek JSON
$id = $data->id;

// Hapus data dari database
$sql = "DELETE FROM berkas_pendukung WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    $response = array('status' => 'success', 'message' => 'Data berhasil dihapus.');
} else {
    $response = array('status' => 'error', 'message' => 'Error: ' . $sql . '<br>' . $conn->error);
}

// Menutup koneksi database
$conn->close();

// Mengembalikan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
