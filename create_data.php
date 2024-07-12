<?php
header('Content-Type: application/json');

// Ganti dengan konfigurasi database Anda
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_sicaper";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Mengambil nilai dari $_POST
$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : null;
$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

// Memeriksa apakah semua field diisi
if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    $conn->close();
    exit();
}

// Memeriksa apakah email sudah ada di database
$email_check_query = "SELECT email FROM data_akun WHERE email = ?";
$stmt = $conn->prepare($email_check_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already exists.']);
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->close();

// Menyisipkan data ke dalam database
$insert_query = "INSERT INTO data_akun (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_query);
$insert_stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

if ($insert_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Data added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding data: ' . $conn->error]);
}

$insert_stmt->close();
$conn->close();
?>
