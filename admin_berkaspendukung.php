<?php
// Koneksi ke database (sesuaikan dengan konfigurasi Anda)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_sicaper";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Query untuk mengambil data berkas_pendukung
$sql = "SELECT * FROM berkas_pendukung";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Array untuk menyimpan data hasil query
    $data = array();

    // Loop through hasil query dan masukkan ke array $data
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'id' => $row['id'],
            'email' => $row['email'],
            'ijazah' => $row['ijazah'],
            'transkrip_nilai' => $row['transkrip_nilai'],
            'surat_sehat' => $row['surat_sehat']
        );
    }

    // Mengembalikan data dalam format JSON
    echo json_encode($data);
} else {
    // Jika tidak ada data ditemukan
    echo json_encode(array());
}

// Tutup koneksi
$conn->close();
?>
