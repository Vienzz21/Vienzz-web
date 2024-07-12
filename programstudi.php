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

$timeout_duration = 10; // 10 seconds

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

// Periksa apakah ada data yang dikirim dari formulir dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah email sudah ada dalam sesi
    if (!isset($_SESSION['email'])) {
        echo json_encode(['success' => false, 'message' => 'Sesi telah berakhir']);
        exit();
    }

    // Ambil nilai email dari sesi
    $email = $_SESSION['email'];

    // Ambil nilai jurusan dan program_studi dari formulir
    $jurusan = $_POST["jurusan"] ?? '';
    $program_studi = $_POST["program_studi"] ?? '';

    // Debugging: Periksa apakah nilai dari formulir diterima dengan benar
    if (empty($jurusan) || empty($program_studi)) {
        echo json_encode(['success' => false, 'message' => 'Semua data harus diisi.']);
        exit();
    }

    // Periksa apakah entri untuk kombinasi email, jurusan, dan program studi sudah ada
    $checkDuplicateQuery = "SELECT * FROM program_studii WHERE email = ? AND jurusan = ? AND program_studi = ?";
    $stmt = $conn->prepare($checkDuplicateQuery);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Prepare statement error: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("sss", $email, $jurusan, $program_studi);
    $stmt->execute();
    $stmt->store_result();

    // Jika sudah ada entri, kirim pesan kesalahan
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Anda sudah mengirimkan data untuk jurusan dan program studi ini sebelumnya.']);
        exit();
    }

    // Query SQL untuk menyimpan data ke tabel program_studi
    $insertDataQuery = "INSERT INTO program_studii (jurusan, program_studi, email) VALUES (?, ?, ?)";

    // Persiapan statement
    $stmt = $conn->prepare($insertDataQuery);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Prepare statement error: ' . $conn->error]);
        exit();
    }

    // Binding parameter ke pernyataan persiapan
    $stmt->bind_param("sss", $jurusan, $program_studi, $email);

    // Eksekusi pernyataan
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data ke database: ' . $stmt->error]);
    }

    // Tutup pernyataan
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Metode permintaan tidak valid.']);
}

// Tutup koneksi
$conn->close();
?>
