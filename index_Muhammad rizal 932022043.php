<?php
session_start();

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
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Debugging: Periksa apakah nilai dari formulir diterima dengan benar
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email dan password harus diisi.']);
        exit();
    }

    // Periksa apakah email terdaftar dan cocok dengan password yang diberikan
    $checkEmailQuery = "SELECT firstname, lastname FROM data_akun WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Prepare statement error: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    // Ambil nama mahasiswa dari hasil query
    $stmt->bind_result($firstname, $lastname);
    $stmt->fetch();

    // Debugging: Periksa apakah email ditemukan di database
    if ($stmt->num_rows > 0) {
        // Kirim respons JSON yang berisi nama mahasiswa
        $_SESSION['email'] = $email; // Set session email
        echo json_encode(['success' => true, 'firstname' => $firstname, 'lastname' => $lastname]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email atau password salah.']);
    }

    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
