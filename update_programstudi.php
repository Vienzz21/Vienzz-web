<?php
// Koneksi ke database menggunakan PDO
$host = 'localhost';
$dbname = 'web_sicaper';
$username = 'root';
$password = '';

// Mendapatkan data dari request POST
$input = json_decode(file_get_contents('php://input'), true);

// Update data di dalam database atau lakukan operasi lain sesuai kebutuhan
// Contoh update sederhana menggunakan PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $input['id'];
    $jurusan = $input['jurusan'];
    $program_studi = $input['program_studi'];
    $email = $input['email'];

    $stmt = $pdo->prepare('UPDATE program_studii SET jurusan = :jurusan, program_studi = :program_studi, email = :email WHERE id = :id');
    $stmt->execute(array(
        ':id' => $id,
        ':jurusan' => $jurusan,
        ':program_studi' => $program_studi,
        ':email' => $email
    ));

    // Response JSON jika berhasil
    $response = array('message' => 'Data program studi berhasil diperbarui');
    echo json_encode($response);
} catch(PDOException $e) {
    // Response JSON jika terjadi error
    $response = array('message' => 'Gagal memperbarui data program studi: ' . $e->getMessage());
    echo json_encode($response);
}
?>
