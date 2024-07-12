<?php
// Ambil data yang dikirim dari frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validasi data yang diterima
if (empty($data['id']) || empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(array('message' => 'Gagal memperbarui data. Mohon lengkapi semua field.'));
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

    // Query untuk update data di tabel data_akun
    $sql = "UPDATE data_akun SET firstname = :firstname, lastname = :lastname, email = :email, password = :password WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Bind parameter
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':firstname', $data['firstname']);
    $stmt->bindParam(':lastname', $data['lastname']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);

    // Eksekusi statement
    $stmt->execute();

    // Kirim response ke frontend
    http_response_code(200);
    echo json_encode(array('message' => 'Data berhasil diperbarui.'));
} catch (PDOException $e) {
    // Tangani kesalahan jika terjadi
    http_response_code(500);
    echo json_encode(array('message' => 'Gagal memperbarui data. Error: ' . $e->getMessage()));
}
?>
