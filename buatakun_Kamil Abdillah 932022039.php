<?php
header('Content-Type: application/json');

// Gantilah nilai-nilai di bawah ini sesuai dengan pengaturan Anda
$servername = "localhost";
$username = "root";
$password = "";
$database = "web_sicaper";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit();
}

// Periksa apakah ada data yang dikirim dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari formulir
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"]; // Simpan password tanpa di-hash

    // Periksa apakah email sudah terdaftar
    $checkEmailQuery = "SELECT id FROM data_akun WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar.']);
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();

    // Query SQL untuk menyimpan data ke dalam database
    $sql = "INSERT INTO data_akun (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);

    // Jalankan query
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
