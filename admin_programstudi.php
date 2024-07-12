<?php
// Database connection
$host = 'localhost'; // sesuaikan dengan host Anda
$username = 'root'; // sesuaikan dengan username Anda
$password = ''; // sesuaikan dengan password Anda
$database = 'web_sicaper';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    // Set error mode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query data from program_studi
    $query_program_studi = $conn->query("SELECT * FROM program_studii");
    $program_studi_rows = $query_program_studi->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($program_studi_rows);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
