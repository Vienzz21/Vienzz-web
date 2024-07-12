<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "web_sicaper");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan data JSON dari request body
$data = json_decode(file_get_contents("php://input"), true);

// Menyiapkan dan menjalankan query
$stmt = $conn->prepare("DELETE FROM data_siswa WHERE id = ?");
$stmt->bind_param("i", $data['id']);

if ($stmt->execute()) {
    echo json_encode(["message" => "Data berhasil dihapus"]);
} else {
    echo json_encode(["message" => "Gagal menghapus data"]);
}

// Menutup koneksi
$stmt->close();
$conn->close();
?>
