<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_sicaper";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data siswa dari database
$sql = "SELECT * FROM data_siswa";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'id' => $row['id'],
            'nama' => $row['nama'],
            'nisn' => $row['nisn'],
            'alamat' => $row['alamat'],
            'no_telpon' => $row['no_telpon'],
            'asal_sekolah' => $row['asal_sekolah'],
            'nilai_akhir' => $row['nilai_akhir'],
            'email' => $row['email']
        );
    }
}

// Mengirimkan data dalam format JSON
echo json_encode($data);

$conn->close();
?>
