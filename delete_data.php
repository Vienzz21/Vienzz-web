<?php
// Ambil data yang dikirim dari frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validasi data yang diterima
if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(array('message' => 'Gagal menghapus data. ID tidak ditemukan.'));
    exit;
}

// Koneksi ke database menggunakan PDO
$host = 'localhost';
$dbname = 'web_sicaper';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query untuk hapus data dari tabel data_akun
    $sql = "DELETE FROM data_akun WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Bind parameter
    $stmt->bindParam(':id', $data['id']);

    // Eksekusi statement
    $stmt->execute();

    // Kirim response ke frontend
    http_response_code(200);
    echo json_encode(array('message' => 'Data berhasil dihapus.'));
} catch (PDOException $e) {
    // Tangani kesalahan jika terjadi
    http_response_code(500);
    echo json_encode(array('message' => 'Gagal menghapus data. Error: ' . $e->getMessage()));
}
?>
