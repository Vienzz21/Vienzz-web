<?php
// Koneksi ke database menggunakan PDO
$host = 'localhost';
$dbname = 'web_sicaper';
$username = 'root';
$password = '';

// Mendapatkan data dari request POST
$input = json_decode(file_get_contents('php://input'), true);

// Hapus data dari database atau lakukan operasi lain sesuai kebutuhan
// Contoh penghapusan sederhana menggunakan PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $input['id'];

    $stmt = $pdo->prepare('DELETE FROM program_studii WHERE id = :id');
    $stmt->execute(array(
        ':id' => $id
    ));

    // Response JSON jika berhasil
    $response = array('message' => 'Data program studi berhasil dihapus');
    echo json_encode($response);
} catch(PDOException $e) {
    // Response JSON jika terjadi error
    $response = array('message' => 'Gagal menghapus data program studi: ' . $e->getMessage());
    echo json_encode($response);
}
?>
