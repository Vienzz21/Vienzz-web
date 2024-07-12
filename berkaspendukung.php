<?php
session_start();

// Ganti nilai-nilai di bawah ini sesuai dengan pengaturan Anda
$servername = "localhost";
$username = "root";
$password = "";
$database = "web_sicaper";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    echo json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]);
    exit();
}

$timeout_duration = 600; // 10 minutes

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
    echo json_encode(["error" => "Sesi telah berakhir."]);
    exit();
} else {
    // Refresh the last activity time
    $_SESSION['last_activity'] = time();
}

// Periksa apakah ada data yang dikirim dari formulir dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah email sudah ada dalam sesi
    if (!isset($_SESSION['email'])) {
        echo json_encode(["error" => "Sesi telah berakhir"]);
        exit();
    }

    // Ambil nilai email dari sesi
    $email = $_SESSION['email'];

    // Fungsi untuk mengunggah berkas
    function uploadFile($inputName, $targetDir)
    {
        if (isset($_FILES[$inputName])) {
            $fileName = basename($_FILES[$inputName]['name']);
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFilePath)) {
                return $targetFilePath;
            } else {
                return null; // Mengembalikan null jika gagal mengunggah berkas
            }
        } else {
            return null; // Mengembalikan null jika variabel $_FILES[$inputName] tidak terdefinisi
        }
    }

    // Tentukan direktori untuk menyimpan berkas
    $targetDir1 = 'ijazahfoto/';
    $targetDir2 = 'transkripfoto/';
    $targetDir3 = 'sksfoto/';

    // Unggah berkas dan simpan path berkas ke dalam variabel
    $ijazahPath = uploadFile('ijazah', $targetDir1);
    $transkripNilaiPath = uploadFile('transkrip_nilai', $targetDir2);
    $suratSehatPath = uploadFile('surat_sehat', $targetDir3);

    // Periksa apakah berkas berhasil diunggah
    if ($ijazahPath !== null && $transkripNilaiPath !== null && $suratSehatPath !== null) {
        // Query SQL untuk menyimpan data ke tabel berkas_pendukung
        $insertDataQuery = "INSERT INTO berkas_pendukung (email, ijazah, transkrip_nilai, surat_sehat) VALUES (?, ?, ?, ?)";

        // Persiapan statement
        $stmt = $conn->prepare($insertDataQuery);

        // Bind parameter
        $stmt->bind_param("ssss", $email, $ijazahPath, $transkripNilaiPath, $suratSehatPath);

        // Eksekusi pernyataan
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Berkas berhasil diunggah dan data berhasil disimpan."]);
        } else {
            echo json_encode(["error" => "Gagal menyimpan data ke database: " . $stmt->error]);
        }

        // Tutup pernyataan
        $stmt->close();
    } else {
        echo json_encode(["error" => "Maaf, terjadi kesalahan saat mengunggah berkas."]);
    }
} else {
    echo json_encode(["error" => "Metode permintaan tidak valid."]);
}

// Tutup koneksi
$conn->close();
?>
