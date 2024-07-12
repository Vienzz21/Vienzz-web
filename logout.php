<?php
session_start();

// Menghapus semua session
session_destroy();

// Mengarahkan pengguna kembali ke halaman login
header("HTTP/1.0 404 Not Found"); 
exit();
?>
