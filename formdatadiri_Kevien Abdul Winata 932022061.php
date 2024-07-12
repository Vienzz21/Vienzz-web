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

$timeout_duration = 600; // 600 seconds (10 minutes)

function isSessionExpired() {
    global $timeout_duration;

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
        return true;
    }
    $_SESSION['last_activity'] = time();
    return false;
}

// Logout if session expired
if (isSessionExpired()) {
    session_unset();
    session_destroy();
    header("HTTP/1.0 404 Not Found");
    exit();
} else {
    // Refresh the last activity time
    $_SESSION['last_activity'] = time();
}

// Tangkap data dari formulir jika ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap nilai input
    $nama = htmlspecialchars($_POST['nama'] ?? '');
    $nisn = htmlspecialchars($_POST['nisn'] ?? '');
    $alamat = htmlspecialchars($_POST['alamat'] ?? '');
    $no_telepon = htmlspecialchars($_POST['no_telepon'] ?? '');
    $asal_sekolah = htmlspecialchars($_POST['asal_sekolah'] ?? '');
    $nilai_akhir = htmlspecialchars($_POST['nilai_akhir'] ?? '');

    // Validasi data
    if (empty($nama) || empty($nisn) || empty($alamat) || empty($no_telepon) || empty($asal_sekolah) || empty($nilai_akhir)) {
        echo json_encode(array("error" => "Harap lengkapi semua bidang."));
        exit();
    }

    // Ambil email dari session
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
    } else {
        // Redirect to 404 if no email in session
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    // Prepare and bind statement
    $stmt = $conn->prepare("SELECT * FROM data_siswa WHERE nisn = ? AND email = ?");
    $stmt->bind_param("ss", $nisn, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // NISN already exists
        echo json_encode(array("exists" => true));
        exit();
    } else {
        // Prepare and bind statement for insertion
        $stmt = $conn->prepare("INSERT INTO data_siswa (nama, nisn, alamat, no_telpon, asal_sekolah, nilai_akhir, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nama, $nisn, $alamat, $no_telepon, $asal_sekolah, $nilai_akhir, $email);

        if ($stmt->execute()) {
            // Data berhasil disimpan
            echo json_encode(array("success" => true));
            exit();
        } else {
            echo json_encode(array("error" => "Error: " . $stmt->error));
            exit();
        }
    }
}

$conn->close();
?>
