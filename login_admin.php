<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "web_sicaper";

// Koneksi ke database
$db = mysqli_connect($servername, $username, $password, $database);

// Periksa koneksi
if (!$db) {
    die("Gagal terhubung dengan database: " . mysqli_connect_error());
}

echo "Berhasil terkoneksi dengan database.<br>";

// Hardcoded username dan password
$valid_username = 'user';
$valid_password = 'user';

// Cek apakah form login telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa apakah username dan password sesuai
    if ($username === $valid_username && $password === $valid_password) {
        // Jika sesuai, set session dan redirect ke halaman admin
        $_SESSION['loggedin'] = true;
        header("Location: admin_page.php");
        exit;
    } else {
        // Jika tidak sesuai, tampilkan pesan error
        $error = "Username atau password salah.";
    }
}
?>