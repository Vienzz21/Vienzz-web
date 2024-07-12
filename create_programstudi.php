<?php
// Koneksi ke database menggunakan PDO
$host = 'localhost';
$dbname = 'web_sicaper';
$username = 'root';
$password = '';

// Mendapatkan data dari request POST
$input = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tambahkan debug untuk melihat data yang diterima dari front-end
    error_log('Received JSON input: ' . print_r($input, true));

    $jurusan = $input['jurusan'];
    $program_studi = $input['program_studi'];
    $email = $input['email'];

    $stmt = $pdo->prepare('INSERT INTO program_studii (jurusan, program_studi, email) VALUES (:jurusan, :program_studi, :email)');
    $stmt->execute(array(
        ':jurusan' => $jurusan,
        ':program_studi' => $program_studi,
        ':email' => $email
    ));

    // Response JSON jika berhasil
    $response = array('message' => 'Data program studi berhasil ditambahkan');
    echo json_encode($response);
} catch(PDOException $e) {
    // Response JSON jika terjadi error
    $response = array('message' => 'Gagal menambahkan data program studi: ' . $e->getMessage());
    echo json_encode($response);
    // Tambahan: Tulis pesan error ke log PHP
    error_log('Error creating data: ' . $e->getMessage());
}
?>
