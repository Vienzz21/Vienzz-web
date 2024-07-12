<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Tentukan direktori untuk menyimpan berkas
        $targetDir1 = 'ijazahfoto/';
        $targetDir2 = 'transkripfoto/';
        $targetDir3 = 'sksfoto/';

        // Handle file uploads
        $ijazahFile = $_FILES['ijazahFile'];
        $transkripNilaiFile = $_FILES['transkripNilaiFile'];
        $suratSehatFile = $_FILES['suratSehatFile'];

        // Move uploaded files to desired directory
        $ijazahUploadPath = $targetDir1 . basename($ijazahFile['name']);
        $transkripNilaiUploadPath = $targetDir2 . basename($transkripNilaiFile['name']);
        $suratSehatUploadPath = $targetDir3 . basename($suratSehatFile['name']);

        if (move_uploaded_file($ijazahFile['tmp_name'], $ijazahUploadPath) &&
            move_uploaded_file($transkripNilaiFile['tmp_name'], $transkripNilaiUploadPath) &&
            move_uploaded_file($suratSehatFile['tmp_name'], $suratSehatUploadPath)) {

            // Database connection (adjust as needed)
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "web_sicaper";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Koneksi database gagal: " . $conn->connect_error);
            }

            // SQL query untuk menyimpan data ke database
            $sql = "INSERT INTO berkas_pendukung (email, ijazah, transkrip_nilai, surat_sehat)
                    VALUES ('$email', '$ijazahUploadPath', '$transkripNilaiUploadPath', '$suratSehatUploadPath')";

            if ($conn->query($sql) === TRUE) {
                $response = array("success" => true, "message" => "Data berhasil ditambahkan.");
                echo json_encode($response);
            } else {
                $response = array("success" => false, "message" => "Gagal menambahkan data: " . $conn->error);
                echo json_encode($response);
            }

            $conn->close();

        } else {
            $response = array("success" => false, "message" => "Gagal mengunggah file.");
            echo json_encode($response);
        }

    } else {
        $response = array("success" => false, "message" => "Email tidak boleh kosong.");
        echo json_encode($response);
    }
} else {
    $response = array("success" => false, "message" => "Metode tidak diizinkan.");
    echo json_encode($response);
}
?>
