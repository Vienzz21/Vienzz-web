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
$stmt = $conn->prepare("UPDATE data_siswa SET nama = ?, nisn = ?, alamat = ?, no_telpon = ?, asal_sekolah = ?, nilai_akhir = ?, email = ? WHERE id = ?");
$stmt->bind_param("sssssssi", $data['nama'], $data['nisn'], $data['alamat'], $data['no_telpon'], $data['asal_sekolah'], $data['nilai_akhir'], $data['email'], $data['id']);

if ($stmt->execute()) {
    echo json_encode(["message" => "Data berhasil diperbarui"]);
} else {
    echo json_encode(["message" => "Gagal memperbarui data"]);
}

// Menutup koneksi
$stmt->close();
$conn->close();
?>
