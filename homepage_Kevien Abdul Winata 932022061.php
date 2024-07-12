<?php
session_start();
    

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_sicaper";
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if (isset($_SESSION['email'])) {
    // Session email ada, pengguna telah login
    $email = $_SESSION['email'];

    // Mendapatkan nama mahasiswa dari database berdasarkan email pengguna yang sedang login
    $stmt = $conn->prepare("SELECT firstname, lastname FROM data_akun WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data dari setiap baris
        $row = $result->fetch_assoc();
        $response = array(
            'success' => true,
            'firstname' => $row["firstname"],
            'lastname' => $row["lastname"]
        );
        echo json_encode($response);
    } else {
        // Jika email tidak ditemukan dalam database
        $response = array(
            'success' => false,
            'message' => 'Email tidak ditemukan dalam database'
        );
        echo json_encode($response);
    }

    $stmt->close();
} else {
    // Session email tidak ada, pengguna tidak login
    $response = array(
        'success' => false,
        'message' => 'Pengguna tidak login.'
    );
    echo json_encode($response);
}

$conn->close();
?>
