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
$stmt = $conn->prepare("INSERT INTO data_siswa (nama, nisn, alamat, no_telpon, asal_sekolah, nilai_akhir, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $data['nama'], $data['nisn'], $data['alamat'], $data['no_telpon'], $data['asal_sekolah'], $data['nilai_akhir'], $data['email']);

if ($stmt->execute()) {
    echo json_encode(["message" => "Data berhasil ditambahkan"]);
} else {
    echo json_encode(["message" => "Gagal menambahkan data"]);
}

// Menutup koneksi
$stmt->close();
$conn->close();
?>
